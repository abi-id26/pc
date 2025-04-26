<?php
require_once 'includes/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    // Check if we have a last_order_id in the session
    if (isset($_SESSION['last_order_id'])) {
        error_log("No order ID in URL, using last_order_id from session: " . $_SESSION['last_order_id']);
        $order_id = $_SESSION['last_order_id'];
    } else {
        error_log("No order ID provided and no last_order_id in session");
        header('Location: index.php');
        exit();
    }
} else {
    $order_id = $_GET['id'];
}

// Log confirmation page access
error_log("Order confirmation page loaded. Session ID: " . session_id());
error_log("User ID in session: " . ($_SESSION['user_id'] ?? 'Not set'));
error_log("Order ID to display: " . $order_id);

// Get order details
try {
    // Get order info
    $stmt = $pdo->prepare("SELECT o.*, u.email, u.username FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          WHERE o.id = ? AND o.user_id = ?");
    $stmt->execute([$order_id, $_SESSION['user_id'] ?? 0]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        // Debugging info
        error_log("Order not found or doesn't belong to user. Order ID: $order_id, User ID: " . ($_SESSION['user_id'] ?? 'Not logged in'));
        
        // If not found, check if the order exists at all 
        $check_stmt = $pdo->prepare("SELECT id FROM orders WHERE id = ?");
        $check_stmt->execute([$order_id]);
        $order_exists = $check_stmt->fetch();
        
        if ($order_exists) {
            error_log("Order exists but doesn't belong to the current user");
        } else {
            error_log("Order doesn't exist in the database");
        }
        
        header('Location: index.php');
        exit();
    }
    
    // Get order items
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($order_items)) {
        error_log("No items found for order $order_id");
    }
    
} catch (Exception $e) {
    // Log the error
    error_log("Error in order_confirmation.php: " . $e->getMessage());
    
    // Redirect on error
    header('Location: index.php');
    exit();
}

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8 mb-12">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Order Confirmation
        </div>
    </div>

    <!-- Success Message -->
    <div class="wood-card mb-8 overflow-hidden fade-in">
        <div class="py-8 px-6 text-center bg-green-50 border-b border-green-100">
            <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-green-800 mb-2">Order Confirmed!</h1>
            <p class="text-green-700">Thank you for your purchase. Your order has been placed successfully.</p>
            <p class="text-amber-700 mt-2">Order #<?= htmlspecialchars($order_id) ?></p>
            <p class="text-sm text-amber-600 mt-4">A confirmation email has been sent to <?= htmlspecialchars($order['email']) ?></p>
        </div>
        <div class="p-6 card-content relative">
            <div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>
            <div class="relative z-10">
                <h2 class="wood-card-title text-xl mb-6 pb-2 border-b border-amber-200">
                    <i class="fas fa-info-circle mr-2"></i> Order Details
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Customer Information -->
                    <div>
                        <h3 class="font-semibold text-amber-900 mb-3">Customer Information</h3>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <p class="text-amber-800"><strong>Name:</strong> <?= htmlspecialchars($order['username']) ?></p>
                            <p class="text-amber-800"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div>
                        <h3 class="font-semibold text-amber-900 mb-3">Order Summary</h3>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <p class="text-amber-800"><strong>Order Date:</strong> <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                            <p class="text-amber-800"><strong>Order Number:</strong> #<?= htmlspecialchars($order_id) ?></p>
                            <p class="text-amber-800"><strong>Total Amount:</strong> $<?= number_format($order['total'], 2) ?></p>
                            <p class="text-amber-800"><strong>Payment Status:</strong> <span class="text-green-600">Paid</span></p>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <h3 class="wood-card-title text-lg mb-4 pb-2 border-b border-amber-200">
                    <i class="fas fa-shopping-basket mr-2"></i> Items Ordered
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead>
                            <tr class="bg-amber-100 text-amber-800 text-left">
                                <th class="p-3 rounded-tl-lg">Product</th>
                                <th class="p-3">Price</th>
                                <th class="p-3">Quantity</th>
                                <th class="p-3 rounded-tr-lg text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $index => $item): ?>
                                <?php $is_last = $index === count($order_items) - 1; ?>
                                <tr class="border-b border-amber-100">
                                    <td class="p-3">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-amber-50 rounded overflow-hidden mr-3 border border-amber-200 flex-shrink-0">
                                                <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                                     class="w-full h-full object-contain" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>">
                                            </div>
                                            <span class="font-medium text-amber-900"><?= htmlspecialchars($item['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-amber-800">$<?= number_format($item['price'], 2) ?></td>
                                    <td class="p-3 text-amber-800"><?= $item['quantity'] ?></td>
                                    <td class="p-3 text-right font-bold text-amber-900">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Totals -->
                <div class="mt-6 bg-amber-50 p-4 rounded-lg border border-amber-200 max-w-xs ml-auto">
                    <div class="flex justify-between pb-2 mb-2 border-b border-amber-100">
                        <span class="text-amber-800">Subtotal:</span>
                        <span class="font-medium text-amber-900">$<?= number_format($order['total'] * 0.93, 2) ?></span>
                    </div>
                    <div class="flex justify-between pb-2 mb-2 border-b border-amber-100">
                        <span class="text-amber-800">Shipping:</span>
                        <span class="font-medium text-amber-900">$10.00</span>
                    </div>
                    <div class="flex justify-between pb-2 mb-2 border-b border-amber-100">
                        <span class="text-amber-800">Tax (7%):</span>
                        <span class="font-medium text-amber-900">$<?= number_format($order['total'] * 0.07, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-lg pt-1 font-bold">
                        <span class="text-amber-900">Total:</span>
                        <span class="text-amber-900">$<?= number_format($order['total'], 2) ?></span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 justify-center mt-10">
                    <a href="index.php" class="wooden-cart-button px-6 py-3">
                        <i class="fas fa-home mr-2"></i>
                        <span class="button-text">Continue Shopping</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recommended Products -->
    <div class="wood-card overflow-hidden fade-in">
        <div class="bg-amber-800 py-4 px-6 border-b border-amber-700">
            <h3 class="text-xl font-bold text-white">
                <i class="fas fa-thumbs-up mr-2"></i> You Might Also Like
            </h3>
        </div>
        <div class="p-6 card-content relative">
            <div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>
            <div class="relative z-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php
                    // Get some recommended products (different from what was ordered)
                    $ordered_product_ids = array_column($order_items, 'product_id');
                    $ordered_product_ids_str = implode(',', array_fill(0, count($ordered_product_ids), '?'));
                    
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id NOT IN (" . 
                                          ($ordered_product_ids_str ?: '0') . ") ORDER BY RAND() LIMIT 4");
                    $stmt->execute($ordered_product_ids ?: [0]);
                    $recommended_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($recommended_products as $product):
                    ?>
                        <div class="product-card p-4 rounded-lg bg-amber-50 border border-amber-200 hover:shadow-lg transition-shadow duration-300">
                            <a href="product.php?id=<?= $product['id'] ?>" class="block">
                                <div class="product-image h-40 mb-4 flex items-center justify-center bg-white rounded-md overflow-hidden">
                                    <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                                         class="max-h-full max-w-full object-contain" 
                                         alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <h3 class="product-title font-medium text-amber-900 mb-2"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-price text-amber-800 font-bold mb-2">$<?= number_format($product['price'], 2) ?></p>
                                <div class="product-action">
                                    <span class="wooden-cart-button inline-block w-full text-center py-2">
                                        <i class="fas fa-eye mr-2"></i>
                                        <span class="button-text">View Product</span>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add wood texture to cards
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    
    // Animation
    setTimeout(function() {
        $('.fade-in').addClass('opacity-100');
    }, 100);
});
</script>

<?php require_once 'includes/footer.php'; ?>