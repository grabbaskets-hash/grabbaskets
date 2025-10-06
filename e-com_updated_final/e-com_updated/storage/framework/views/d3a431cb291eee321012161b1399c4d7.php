<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
        }
        .highlight {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 New Order Received!</h1>
    </div>
    
    <div class="content">
        <h2>Hello <?php echo e($user->name); ?>,</h2>
        
        <p>Great news! You have received a new order for your product.</p>
        
        <div class="order-details">
            <h3>Order Details:</h3>
            <p><strong>Product:</strong> <?php echo e($product->name); ?></p>
            <p><strong>Order ID:</strong> #<?php echo e($order->id); ?></p>
            <p><strong>Amount:</strong> <span class="highlight">₹<?php echo e(number_format($order->amount, 2)); ?></span></p>
            <p><strong>Buyer:</strong> <?php echo e($order->buyerUser->name); ?></p>
            <p><strong>Order Date:</strong> <?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
            <p><strong>Payment Status:</strong> <span class="highlight"><?php echo e(ucfirst($order->status)); ?></span></p>
        </div>
        
        <div class="order-details">
            <h3>Delivery Address:</h3>
            <p><?php echo e($order->delivery_address); ?><br>
            <?php echo e($order->delivery_city); ?>, <?php echo e($order->delivery_state); ?><br>
            PIN: <?php echo e($order->delivery_pincode); ?></p>
        </div>
        
        <p>Please log in to your seller dashboard to view more details and update the order status.</p>
        <div class="order-details" style="background:#fffbe6; border:1px solid #ffe58f;">
            <strong>Reminder:</strong> After you courier the package, please enter the tracking number for this order in your dashboard. The buyer will be notified automatically.
        </div>
        <p style="margin-top: 30px;">
            <a href="<?php echo e(route('seller.orders')); ?>" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Order Details</a>
        </p>
    </div>
    
    <div class="footer">
        <p>Thank you for selling with <?php echo e(config('app.name')); ?>!</p>
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/emails/seller-order-notification.blade.php ENDPATH**/ ?>