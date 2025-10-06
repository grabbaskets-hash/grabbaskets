<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\InfobipSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpayId;
    private $razorpayKey;

    public function __construct()
    {
        $this->razorpayId = config('services.razorpay.key');
        $this->razorpayKey = config('services.razorpay.secret');
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'new_address' => 'nullable|string|max:255',
            'address_type' => 'nullable|in:home,office,other',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => ['required', 'string', 'max:10', 'regex:/^[0-9]{5,10}$/'],
        ]);

        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        $totals = $this->calculateTotals($items);
        $address = $request->new_address ?: $request->address;

        // Save new address if provided
        if ($request->new_address) {
            \App\Models\Address::create([
                'user_id' => Auth::id(),
                'address' => $address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'type' => $request->address_type,
            ]);
        }

        // Create Razorpay order
        $api = new Api($this->razorpayId, $this->razorpayKey);
        
        $orderData = [
            'receipt'         => 'order_' . uniqid(),
            'amount'          => $totals['total'] * 100, // Convert to paise
            'currency'        => 'INR',
            'payment_capture' => 1
        ];

        $razorpayOrder = $api->order->create($orderData);

        // Store order details in session for later use
        session([
            'checkout_data' => [
                'address' => $address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'items' => $items->toArray(),
                'totals' => $totals,
                'razorpay_order_id' => $razorpayOrder['id']
            ]
        ]);

        return response()->json([
            'order_id' => $razorpayOrder['id'],
            'amount' => $totals['total'] * 100,
            'currency' => 'INR',
            'name' => config('app.name'),
            'description' => 'Payment for order',
            'prefill' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ]);
    }

    public function verifyPayment(Request $request)
    {
        // Stock validation before payment processing
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return response()->json(['error' => 'Checkout session expired'], 400);
        }
        foreach ($checkoutData['items'] as $itemData) {
            $item = (object) $itemData;
            $product = Product::find($item->product_id);
            $qty = $item->quantity ?? 1;
            if (!$product || $product->stock < $qty) {
                $productName = $product ? $product->name : 'Unknown';
                return response()->json([
                    'error' => "Product '{$productName}' is out of stock or does not have enough quantity. Please update your cart."
                ], 400);
            }
        }
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $api = new Api($this->razorpayId, $this->razorpayKey);

        try {
            $checkoutData = session('checkout_data');
            
            if (!$checkoutData) {
                return response()->json(['error' => 'Checkout session expired'], 400);
            }

            // Verify payment signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified, create orders
            $orders = [];
            foreach ($checkoutData['items'] as $itemData) {
                $item = (object) $itemData;
                $amount = $this->lineAmountFromData($item);
                
                $order = Order::create([
                    'product_id' => $item->product_id,
                    'seller_id' => $item->seller_id,
                    'buyer_id' => Auth::id(),
                    'amount' => $amount,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_reference' => $request->razorpay_payment_id,
                    'delivery_address' => $checkoutData['address'],
                    'delivery_city' => $checkoutData['city'],
                    'delivery_state' => $checkoutData['state'],
                    'delivery_pincode' => $checkoutData['pincode'],
                    'payment_method' => 'razorpay',
                ]);

                $orders[] = $order;

                // Create notification for seller
                $product = Product::find($item->product_id);
                // Decrease product stock
                if ($product && $product->stock > 0) {
                    $product->stock = max(0, $product->stock - ($item->quantity ?? 1));
                    $product->save();
                }
                $seller = User::find($item->seller_id);
                
                if ($seller) {
                    // Use NotificationService for Amazon-like notifications
                    NotificationService::sendNewOrderToSeller($seller, $order);
                    
                    // Send email to seller
                    $this->sendOrderNotificationEmail($seller, $order, $product, 'seller');
                    
                    // 📱 Send SMS notification to seller
                    $smsService = new InfobipSmsService();
                    $smsResult = $smsService->sendOrderConfirmationToSeller($seller, $order);
                    if ($smsResult['success']) {
                        Log::info('SMS sent to seller', ['seller_id' => $seller->id, 'order_id' => $order->id]);
                    } else {
                        Log::warning('Failed to send SMS to seller', ['seller_id' => $seller->id, 'error' => $smsResult['error']]);
                    }
                }

                // Create notification for buyer using NotificationService
                NotificationService::sendOrderPlaced(Auth::user(), $order);
                NotificationService::sendPaymentConfirmed(Auth::user(), $order);
            }

            // Send email to buyer
            $this->sendOrderNotificationEmail(Auth::user(), $orders[0], null, 'buyer', $orders);
            
            // 📱 Send SMS payment confirmation to buyer
            $smsService = new InfobipSmsService();
            $buyerSmsResult = $smsService->sendPaymentConfirmationToBuyer(Auth::user(), $orders[0]);
            if ($buyerSmsResult['success']) {
                Log::info('Payment confirmation SMS sent to buyer', ['buyer_id' => Auth::id(), 'order_count' => count($orders)]);
            } else {
                Log::warning('Failed to send payment confirmation SMS to buyer', ['buyer_id' => Auth::id(), 'error' => $buyerSmsResult['error']]);
            }

            // Clear cart and session
            CartItem::where('user_id', Auth::id())->delete();
            session()->forget('checkout_data');

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your orders have been placed.',
                'redirect' => route('orders.track')
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 400);
        }
    }

    private function calculateTotals($items)
    {
        $subtotal = 0;
        $discountTotal = 0;
        $deliveryTotal = 0;
        
        foreach ($items as $item) {
            $price = (float) $item->price;
            $discPct = (float) $item->discount;
            $qty = (int) $item->quantity;
            $delivery = (float) $item->delivery_charge;

            $lineBase = $price * $qty;
            $lineDisc = $discPct > 0 ? ($lineBase * ($discPct / 100)) : 0;
            $subtotal += $lineBase;
            $discountTotal += $lineDisc;
            $deliveryTotal += $delivery;
        }
        
        $total = $subtotal - $discountTotal + $deliveryTotal;
        return compact('subtotal', 'discountTotal', 'deliveryTotal', 'total');
    }

    private function lineAmountFromData($item): float
    {
        $price = (float) $item->price;
        $discPct = (float) $item->discount;
        $qty = (int) $item->quantity;
        $delivery = (float) $item->delivery_charge;
        
        $lineBase = $price * $qty;
        $lineDisc = $discPct > 0 ? ($lineBase * ($discPct / 100)) : 0;
        return $lineBase - $lineDisc + $delivery;
    }

    private function sendOrderNotificationEmail($user, $order, $product, $type, $allOrders = null)
    {
        try {
            $data = [
                'user' => $user,
                'order' => $order,
                'product' => $product,
                'type' => $type,
                'orders' => $allOrders
            ];

            if ($type === 'seller') {
                Mail::send('emails.seller-order-notification', $data, function ($message) use ($user, $product) {
                    $message->to($user->email)
                            ->subject('New Order Received - ' . $product->name);
                });
            } else {
                Mail::send('emails.buyer-order-confirmation', $data, function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Order Confirmation - ' . config('app.name'));
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order notification email: ' . $e->getMessage());
        }
    }
}