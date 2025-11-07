<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;

class SellerNewOrder extends Notification
{
    use Queueable;

    protected $order;
    protected $product;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $product)
    {
        $this->order = $order;
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail'];
        
        // Add SMS channel if phone number is available
        if ($notifiable->phone) {
            $channels[] = TwilioChannel::class;
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order Received - ' . $this->product->name)
            ->view('emails.seller-order-notification', [
                'user' => $notifiable,
                'order' => $this->order,
                'product' => $this->product
            ]);
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toTwilio(object $notifiable): string
    {
        $orderId = $this->order->id;
        $amount = number_format($this->order->amount, 2);
        $productName = $this->product->name;
        $buyerName = $this->order->buyerUser->name ?? 'Customer';
        
        return "🎉 New Order! {$notifiable->name}, you received an order #{$orderId} "
            . "for {$productName} (₹{$amount}) from {$buyerName}. "
            . "Login to your seller dashboard to view details and ship the order!";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'product_name' => $this->product->name,
            'amount' => $this->order->amount,
            'buyer_name' => $this->order->buyerUser->name ?? 'Customer',
            'type' => 'new_order',
        ];
    }
}
