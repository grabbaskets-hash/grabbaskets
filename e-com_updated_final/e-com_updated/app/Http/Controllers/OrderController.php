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

        return back()->with('success', 'Tracking information updated and buyer notified with tracking details!');
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
}