<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">New Order Received</h2>
        
        <p>Hello Admin,</p>
        
        <p>A new order has been placed on your website. Here are the details:</p>
        
        <div style="background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #2d3748;">Order Information</h3>
            <p><strong>Order ID:</strong> {{ $order->orderId }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Total Amount:</strong> ৳{{ number_format($order->price, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_type }}</p>
        </div>
        
        <div style="background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #2d3748;">Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->name }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? 'Not provided' }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Address:</strong> {{ $order->address }}</p>
            <p><strong>District:</strong> {{ $order->district->name ?? 'Not specified' }}</p>
            <p><strong>Sub District:</strong> {{ $order->subDistrict->name ?? 'Not specified' }}</p>
        </div>
        
        <div style="background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #2d3748;">Order Items</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #edf2f7;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #e2e8f0;">Product</th>
                        <th style="padding: 10px; text-align: center; border: 1px solid #e2e8f0;">Quantity</th>
                        <th style="padding: 10px; text-align: right; border: 1px solid #e2e8f0;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $item)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #e2e8f0;">{{ $item->product->name ?? 'Product' }}</td>
                        <td style="padding: 10px; text-align: center; border: 1px solid #e2e8f0;">{{ $item->qty }}</td>
                        <td style="padding: 10px; text-align: right; border: 1px solid #e2e8f0;">৳{{ number_format($item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <p style="margin-top: 30px;">Please log in to your admin panel to view the complete order details and process the order accordingly.</p>
        
        <p>Thank you,<br>
        {{ config('app.name') }} Team</p>
    </div>
</body>
</html>