<?php

namespace App\Services;

use App\Notifications\Channels\TwilioChannel;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    private $twilioChannel;
    private $enabled;
    private $provider;

    public function __construct()
    {
        $this->twilioChannel = new TwilioChannel();
        $this->enabled = config('services.sms.enabled', false);
        $this->provider = config('services.sms.provider', 'twilio');
    }

    /**
     * Send order confirmation SMS to seller
     */
    public function sendOrderConfirmationToSeller($seller, $order)
    {
        if (!$this->enabled) {
            Log::info('SMS disabled, skipping seller order notification');
            return ['success' => false, 'error' => 'SMS notifications disabled'];
        }

        if (!$seller->phone) {
            return ['success' => false, 'error' => 'Seller phone number not available'];
        }

        $productName = $order->product->name ?? 'Product';
        $message = "New Order #{$order->id}! You received an order for {$productName} worth ₹{$order->amount}. Please process it soon. - grabbaskets-TN";

        try {
            $result = $this->twilioChannel->sendSms($seller->phone, $message);
            
            if ($result['success']) {
                Log::info('Order notification SMS sent to seller', [
                    'seller_id' => $seller->id,
                    'order_id' => $order->id,
                    'phone' => $seller->phone
                ]);
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error('Failed to send SMS to seller', [
                'seller_id' => $seller->id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send payment confirmation SMS to buyer
     */
    public function sendPaymentConfirmationToBuyer($buyer, $order)
    {
        if (!$this->enabled) {
            Log::info('SMS disabled, skipping buyer payment notification');
            return ['success' => false, 'error' => 'SMS notifications disabled'];
        }

        if (!$buyer->phone) {
            return ['success' => false, 'error' => 'Buyer phone number not available'];
        }

        $message = "Payment Confirmed! Your order #{$order->id} worth ₹{$order->amount} has been received. We're processing it now. - grabbaskets-TN";

        try {
            $result = $this->twilioChannel->sendSms($buyer->phone, $message);
            
            if ($result['success']) {
                Log::info('Payment confirmation SMS sent to buyer', [
                    'buyer_id' => $buyer->id,
                    'order_id' => $order->id,
                    'phone' => $buyer->phone
                ]);
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error('Failed to send SMS to buyer', [
                'buyer_id' => $buyer->id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send order status update SMS to buyer
     */
    public function sendOrderStatusUpdateToBuyer($buyer, $order, $status)
    {
        if (!$this->enabled) {
            Log::info('SMS disabled, skipping buyer status update');
            return ['success' => false, 'error' => 'SMS notifications disabled'];
        }

        if (!$buyer->phone) {
            return ['success' => false, 'error' => 'Buyer phone number not available'];
        }

        $messages = [
            'processing' => "Your order #{$order->id} is being processed.",
            'shipped' => "Great news! Your order #{$order->id} has been shipped.",
            'out_for_delivery' => "Your order #{$order->id} is out for delivery and will arrive soon!",
            'delivered' => "Your order #{$order->id} has been delivered successfully. Thank you for shopping with us!",
            'cancelled' => "Your order #{$order->id} has been cancelled."
        ];

        $message = $messages[$status] ?? "Your order #{$order->id} status has been updated to: {$status}.";
        $message .= " - grabbaskets-TN";

        try {
            $result = $this->twilioChannel->sendSms($buyer->phone, $message);
            
            if ($result['success']) {
                Log::info('Order status update SMS sent to buyer', [
                    'buyer_id' => $buyer->id,
                    'order_id' => $order->id,
                    'status' => $status,
                    'phone' => $buyer->phone
                ]);
            }
            
            return $result;
        } catch (Exception $e) {
            Log::error('Failed to send status update SMS to buyer', [
                'buyer_id' => $buyer->id,
                'order_id' => $order->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
