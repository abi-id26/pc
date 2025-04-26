<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

requireAdmin();

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Fetch order details with user information
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT 
        p.id, p.name, p.image,
        oi.quantity, oi.price,
        (oi.quantity * oi.price) AS subtotal
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

// Fetch shipping address
$stmt = $pdo->prepare("
    SELECT * FROM shipping_addresses
    WHERE order_id = ?
    ORDER BY id DESC LIMIT 1
");
$stmt->execute([$order_id]);
$shipping_address = $stmt->fetch();

// Calculate subtotal, tax and shipping
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['subtotal'];
}
$shipping = 10.00; // Standard shipping cost
$tax = $subtotal * 0.07; // 7% tax rate

// Set header for printing
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order #<?= $order['id'] ?> - PC Hardware Store</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Clean print styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #333;
            line-height: 1.5;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .print-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .print-logo {
            font-weight: bold;
            font-size: 24px;
        }
        
        .print-title {
            text-align: center;
            font-size: 22px;
            margin: 20px 0;
        }
        
        .print-section {
            margin-bottom: 20px;
        }
        
        .print-section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .print-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .print-info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        
        .print-info-label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        
        .print-info-value {
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f5f5f5;
        }
        
        .text-right {
            text-align: right;
        }
        
        .print-footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        
        .print-buttons {
            text-align: center;
            margin: 20px 0;
        }
        
        .print-button {
            background: #333;
            color: #fff;
            border: 0;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print print-buttons">
            <button type="button" class="print-button" onclick="window.print();">
                <i class="fas fa-print"></i> Print Order
            </button>
            <button type="button" class="print-button" onclick="window.location.href='order_detail.php?id=<?= $order_id ?>'">
                <i class="fas fa-arrow-left"></i> Back to Order
            </button>
        </div>
        
        <div class="print-header">
            <div class="print-logo">PC Hardware Store</div>
            <div>123 Tech Street, Silicon Valley, CA 94043</div>
            <div>Phone: (555) 123-4567 | Email: orders@pchardwarestore.com</div>
        </div>
        
        <h1 class="print-title">ORDER INVOICE #<?= $order_id ?></h1>
        
        <div class="print-grid">
            <div class="print-info-box">
                <div class="print-section-title">Order Information</div>
                <div class="print-info-label">Order Number:</div>
                <div class="print-info-value">#<?= $order_id ?></div>
                
                <div class="print-info-label">Order Date:</div>
                <div class="print-info-value"><?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></div>
                
                <div class="print-info-label">Status:</div>
                <div class="print-info-value"><?= ucfirst($order['status']) ?></div>
            </div>
            
            <div class="print-info-box">
                <div class="print-section-title">Customer Information</div>
                <div class="print-info-label">Customer:</div>
                <div class="print-info-value"><?= htmlspecialchars($order['username']) ?></div>
                
                <div class="print-info-label">Email:</div>
                <div class="print-info-value"><?= htmlspecialchars($order['email']) ?></div>
            </div>
        </div>
        
        <?php if ($shipping_address): ?>
        <div class="print-section">
            <div class="print-section-title">Shipping Address</div>
            <div class="print-info-box">
                <div class="print-info-value">
                    <?= htmlspecialchars($shipping_address['first_name'] . ' ' . $shipping_address['last_name']) ?><br>
                    <?= htmlspecialchars($shipping_address['address']) ?><br>
                    <?= htmlspecialchars($shipping_address['city']) . ', ' . htmlspecialchars($shipping_address['state']) . ' ' . htmlspecialchars($shipping_address['zip']) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="print-section">
            <div class="print-section-title">Order Items</div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="text-right">$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="print-section">
            <div class="print-info-box">
                <table>
                    <tr>
                        <td style="width: 70%; border: none;"></td>
                        <td class="text-right" style="border: none;">Subtotal:</td>
                        <td class="text-right" style="border: none;">$<?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="border: none;"></td>
                        <td class="text-right" style="border: none;">Shipping:</td>
                        <td class="text-right" style="border: none;">$<?= number_format($shipping, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="border: none;"></td>
                        <td class="text-right" style="border: none;">Tax (7%):</td>
                        <td class="text-right" style="border: none;">$<?= number_format($tax, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="border: none;"></td>
                        <td class="text-right" style="border-top: 2px solid #333; font-weight: bold;">TOTAL:</td>
                        <td class="text-right" style="border-top: 2px solid #333; font-weight: bold;">$<?= number_format($order['total'], 2) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="print-section">
            <div class="print-section-title">Payment Information</div>
            <div class="print-info-box">
                <div class="print-info-label">Payment Method:</div>
                <div class="print-info-value">Credit Card</div>
                
                <div class="print-info-label">Payment Status:</div>
                <div class="print-info-value">Paid</div>
            </div>
        </div>
        
        <div class="print-footer">
            <p>Thank you for your business!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>Printed on: <?= date('Y-m-d H:i:s') ?></p>
        </div>
    </div>
    
    <script>
        // Auto-print when the page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html> 