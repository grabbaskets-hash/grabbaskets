<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Services\InfobipSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Cancel an order (buyer)
    public function cancel(Request $request, Order $order)
    {
        // Only the buyer can cancel their order
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancel if status is not shipped or delivered
        $cancellableStatuses = ['pending', 'paid', 'confirmed'];
        if (!in_array($order->status, $cancellableStatuses)) {
            return back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $order->status = 'cancelled';
        $order->save();

        // Send Amazon-like cancellation notification
        NotificationService::sendOrderStatusUpdate(Auth::user(), $order, 'cancelled');

        // Optionally, notify seller and admin
        $seller = $order->sellerUser;
        if ($seller && $seller->email) {
            $subject = 'Order Cancelled by Buyer';
            $message = "Order #{$order->id} has been cancelled by the buyer.";
            Mail::raw($message, function ($mail) use ($seller, $subject) {
                $mail->to($seller->email)
                    ->subject($subject);
            });
        }

        // Optionally, notify admin (if admin email is set in .env)
        $adminEmail = config('mail.admin_email');
        if ($adminEmail) {
            $subject = 'Order Cancelled';
            $message = "Order #{$order->id} has been cancelled by the buyer.";
            Mail::raw($message, function ($mail) use ($adminEmail, $subject) {
                $mail->to($adminEmail)
                    ->subject($subject);
            });
        }

        return back()->with('success', 'Order cancelled successfully.');
    }
    // Show all orders for the logged-in seller
    public function sellerOrders()
    {
        $orders = Order::with(['product', 'buyerUser'])
            ->where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.seller-orders', compact('orders'));
    }
    public function track()
    {
        $orders = Order::with(['product', 'sellerUser'])
            ->where('buyer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.track', compact('orders'));
    }
    public function show(Order $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }
        $order->load(['product', 'sellerUser']);
        return view('orders.show', compact('order'));
    }
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'courier_name' => 'nullable|string|max:255',
        ]);

        // Allow if user is the seller of this product or is an admin
        $user = Auth::user();
        $isSeller = $order->product->seller_id === $user->id;
        $isAdmin = $user->role === 'admin';
        if (!($isSeller || $isAdmin)) {
            abort(403);
        }

        $order->tracking_number = $request->tracking_number;
        $order->courier_name = $request->courier_name ?? 'Unknown Courier';
        $order->save();

        // Create Amazon-style tracking notification
        $buyer = $order->buyerUser;
        if ($buyer) {
            // Create in-app notification
            Notification::create([
                'user_id' => $buyer->id,
                'title' => 'Package Shipped! 📦',
                'message' => "Great news! Your order #{$order->id} has been shipped via {$order->courier_name}. Track it with number: {$order->tracking_number}",
                'type' => 'order_shipped',
                'data' => json_encode([
                    'order_id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'courier_name' => $order->courier_name,
                    'tracking_url' => route('tracking.form') . '?tracking_number=' . $order->tracking_number
                ])
            ]);

            // Send email notification
            if ($buyer->email) {
                $subject = 'Your Order Has Been Shipped! 🚚';
                $trackingUrl = route('tracking.form') . '?tracking_number=' . $order->tracking_number;
                $message = "
Dear {$buyer->name},

Exciting news! Your order #{$order->id} has been shipped and is on its way to you.

📦 Tracking Details:
• Courier: {$order->courier_name}
• Tracking Number: {$order->tracking_number}
• Track Your Package: {$trackingUrl}

You can track your package in real-time using our tracking system. Just click the link above or enter your tracking number on our website.

Thank you for shopping with us!

Best regards,
Grabbasket Team
                ";
                
                Mail::raw($message, function ($mail) use ($buyer, $subject) {
                    $mail->to($buyer->email)
                        ->subject($subject);
                });
            }
        }

        // Update order status to shipped if it's not already
        if ($order->status !== 'shipped' && $order->status !== 'delivered') {
            $order->status = 'shipped';
            $order->save();
        }

        // 📱 Send SMS shipping notification to buyer
        if ($buyer && $buyer->phone) {
            $smsService = new InfobipSmsService();
            $smsResult = $smsService->sendShippingNotificationToBuyer($buyer, $order);
            if ($smsResult['success']) {
                Log::info('Shipping SMS sent to buyer', ['buyer_id' => $buyer->id, 'order_id' => $order->id]);
            } else {
                Log::warning('Failed to send shipping SMS to buyer', ['buyer_id' => $buyer->id, 'error' => $smsResult['error']]);
            }
        }

        return back()->with('success', 'Tracking information updated successfully.');
    }

    // Show all orders for authenticated user
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Please login to view your orders.');
            }

            $orders = Order::where('buyer_id', $user->id)
                ->with(['orderItems.product', 'sellerUser'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Orders Index Error: ' . $e->getMessage());
            
            return view('orders.index', [
                'orders' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]),
                    0,
                    10,
                    1,
                    ['path' => request()->url()]
                ),
                'error' => 'Unable to load orders. Please try again later.'
            ]);
        }
    }

    /**
     * Update order status with Amazon-like notifications
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:confirmed,shipped,out_for_delivery,delivered'
        ]);

        // Allow if user is the seller of this product or is an admin
        $user = Auth::user();
        $isSeller = $order->product->seller_id === $user->id;
        $isAdmin = $user->role === 'admin';
        if (!($isSeller || $isAdmin)) {
            abort(403);
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        $order->status = $newStatus;
        $order->save();

        // Send notification to buyer
        NotificationService::sendOrderStatusUpdate($order->buyerUser, $order, $newStatus);

        // Special handling for delivery completion
        if ($newStatus === 'delivered') {
            // Send review request after 24 hours (in real app, use a queue/job)
            NotificationService::sendReviewRequest($order->buyerUser, $order);
        }

        return back()->with('success', "Order status updated to {$newStatus} and buyer notified.");
    }

    /**
     * Send promotional notifications
     */
    public function sendPromotion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'user_type' => 'required|in:all,buyers,sellers'
        ]);

        // Get users based on type
        $users = collect();
        if ($request->user_type === 'all') {
            $users = \App\Models\User::all();
        } elseif ($request->user_type === 'buyers') {
            $users = \App\Models\User::where('role', 'buyer')->get();
        } elseif ($request->user_type === 'sellers') {
            $users = \App\Models\User::where('role', 'seller')->get();
        }

        // Send bulk notifications
        NotificationService::sendBulkNotification(
            $users->pluck('id')->toArray(),
            'promotion',
            $request->title,
            $request->message
        );

        return back()->with('success', "Promotional notification sent to {$users->count()} users.");
    }

    /**
     * Show live tracking page for an order
     */
    public function liveTracking(Order $order)
    {
        // Verify buyer owns this order
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('orders.live-tracking', compact('order'));
    }

    /**
     * Check if quick delivery is available for given address
     */
    public function checkQuickDelivery(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string',
            'store_id' => 'required|integer'
        ]);

        // Get coordinates from address
        $coordinates = \App\Services\QuickDeliveryService::getCoordinates(
            $request->address,
            $request->city,
            $request->state,
            $request->pincode
        );

        if (!$coordinates) {
            return response()->json([
                'eligible' => false,
                'message' => 'Unable to verify address. Please check and try again.'
            ], 400);
        }

        // Get warehouse coordinates
        // GrabBaskets Warehouse: Mahatma Gandhi Nagar Rd, Near Annai Therasa English School
        // MRR Nagar, Palani Chettipatti, Theni, Tamil Nadu 625531
        $storeLatitude = 10.0103; // Theni, Tamil Nadu
        $storeLongitude = 77.4773; // Theni, Tamil Nadu

        // Check eligibility
        $eligibility = \App\Services\QuickDeliveryService::checkEligibility(
            $coordinates['latitude'],
            $coordinates['longitude'],
            $storeLatitude,
            $storeLongitude
        );

        return response()->json($eligibility);
    }

    /**
     * Assign delivery partner to order
     */
    public function assignDelivery(Order $order)
    {
        // Only seller can assign delivery
        if ($order->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $partner = \App\Services\QuickDeliveryService::assignDeliveryPartner($order);

        // Update order status to shipped
        $order->update(['status' => 'shipped']);

        return back()->with('success', 'Delivery partner assigned: ' . $partner['name']);
    }

    /**
     * API: Get current tracking data for order
     */
    public function apiTrackOrder(Order $order)
    {
        // In production, add proper authentication
        
        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'delivery_type' => $order->delivery_type,
            'latitude' => $order->delivery_latitude,
            'longitude' => $order->delivery_longitude,
            'eta_minutes' => $order->eta_minutes,
            'distance_km' => $order->distance_km,
            'delivery_partner' => [
                'name' => $order->delivery_partner_name,
                'phone' => $order->delivery_partner_phone,
                'vehicle' => $order->delivery_partner_vehicle,
            ],
            'location_updated_at' => $order->location_updated_at,
        ]);
    }

    /**
     * API: Update delivery partner location (called by delivery partner app)
     */
    public function apiUpdateLocation(Request $request, Order $order)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        \App\Services\QuickDeliveryService::updateDeliveryLocation(
            $order,
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'eta_minutes' => $order->fresh()->eta_minutes
        ]);
    }
}
