<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

requireAdmin();

// Fetch all orders with user information
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->fetchAll();

// Get status counts for statistics
$pendingCount = 0;
$completedCount = 0;
$cancelledCount = 0;
$totalRevenue = 0;

foreach ($orders as $order) {
    if ($order['status'] == 'pending') $pendingCount++;
    else if ($order['status'] == 'completed') {
        $completedCount++;
        $totalRevenue += $order['total'];
    }
    else if ($order['status'] == 'cancelled') $cancelledCount++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - PC Hardware Store</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="flex flex-col min-h-screen bg-amber-50">
    <!-- Admin Header -->
    <header class="wooden-header relative overflow-hidden">
        <div class="wooden-texture-overlay"></div>
        <div class="container mx-auto px-4 py-3 relative z-10">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="../index.php" class="store-logo flex items-center text-xl font-bold text-amber-50">
                        <i class="fas fa-desktop mr-2"></i>
                        <span>PC Hardware Store</span>
                    </a>
                    <span class="store-badge ml-4 bg-amber-800 text-white px-3 py-1 rounded-full text-xs">
                        Admin Panel
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-amber-200">
                        <i class="fas fa-user-shield mr-1"></i> 
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                    <a href="../logout.php" class="nav-link text-amber-200 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen bg-amber-50 flex-grow">
        <?php require_once 'includes/sidebar.php'; ?>
        
        <main class="flex-1 p-6">
            <div class="wood-breadcrumbs mb-6">
                <div class="wood-breadcrumb-item">
                    <a href="../index.php"><i class="fas fa-home mr-1"></i> Home</a>
                </div>
                <div class="wood-breadcrumb-item">
                    <a href="index.php">Admin</a>
                </div>
                <div class="wood-breadcrumb-item active">
                    Orders
                </div>
            </div>
            
            <!-- Order Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Orders</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-shopping-cart text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-800"><?= count($orders) ?></p>
                    </div>
                </div>
                
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Pending</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-clock text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-600"><?= $pendingCount ?></p>
                    </div>
                </div>
                
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-green-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Completed</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-check text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-green-600"><?= $completedCount ?></p>
                    </div>
                </div>
                
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-green-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Revenue</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-dollar-sign text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-green-600">$<?= number_format($totalRevenue, 2) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="page-title text-2xl">Order Management</h1>
                <div class="flex space-x-2">
                    <a href="orders.php?status=pending" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-amber-700 text-black">
                        <i class="fas fa-clock mr-2 text-black"></i> Pending
                    </a>
                    <a href="orders.php?status=completed" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-green-600 text-black">
                        <i class="fas fa-check mr-2 text-black"></i> Completed
                    </a>
                    <a href="orders.php" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-amber-800 text-black">
                        <i class="fas fa-list-ul mr-2 text-black"></i> All Orders
                    </a>
                </div>
            </div>
            
            <div class="wood-card overflow-hidden rounded-lg shadow-md mb-8">
                <div class="wooden-texture-overlay"></div>
                <div class="p-6 relative z-10">
                    <div class="overflow-x-auto">
                        <table class="wood-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Order ID</th>
                                    <th class="text-left">Customer</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($orders) > 0): ?>
                                    <?php 
                                    // Filter orders by status if requested
                                    $filteredOrders = $orders;
                                    if (isset($_GET['status']) && $_GET['status']) {
                                        $filteredOrders = array_filter($orders, function($order) {
                                            return $order['status'] == $_GET['status'];
                                        });
                                    }
                                    
                                    if (count($filteredOrders) > 0):
                                        foreach ($filteredOrders as $order): 
                                    ?>
                                    <tr>
                                        <td class="font-medium">#<?= $order['id'] ?></td>
                                        <td>
                                            <div class="font-medium text-amber-900"><?= htmlspecialchars($order['username']) ?></div>
                                            <div class="text-sm text-amber-700"><?= htmlspecialchars($order['email']) ?></div>
                                        </td>
                                        <td><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td class="text-right font-bold text-amber-900">$<?= number_format($order['total'], 2) ?></td>
                                        <td class="text-center">
                                            <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?= 
                                                ($order['status'] == 'completed') ? 'bg-green-600' : 
                                                (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                            ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="order_detail.php?id=<?= $order['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-amber-700 text-black">
                                                    <i class="fas fa-eye mr-1 text-black"></i> View
                                                </a>
                                                <a href="print_order.php?id=<?= $order['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-blue-600 text-white" target="_blank">
                                                    <i class="fas fa-print mr-1 text-black"></i> Print
                                                </a>
                                                <?php if ($order['status'] == 'pending'): ?>
                                                    <a href="update_order.php?id=<?= $order['id'] ?>&status=completed" 
                                                       class="wooden-cart-button inline-flex items-center px-3 py-1 bg-green-600 text-white"
                                                       onclick="return confirm('Mark this order as completed?')">
                                                        <i class="fas fa-check mr-1 text-black"></i> Complete
                                                    </a>
                                                    <a href="update_order.php?id=<?= $order['id'] ?>&status=cancelled" 
                                                       class="wooden-cart-button inline-flex items-center px-3 py-1 bg-red-600 text-white"
                                                       onclick="return confirm('Are you sure you want to cancel this order?')">
                                                        <i class="fas fa-times mr-1 text-black"></i> Cancel
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-amber-800">No orders found matching the current filter.</td>
                                    </tr>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-amber-800">No orders have been placed yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order Processing Guide -->
            <div class="wood-card overflow-hidden rounded-lg shadow-md">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h3 class="text-lg font-bold mb-0 flex items-center text-black">
                        <i class="fas fa-info-circle mr-2 text-black"></i> Order Processing Guide
                    </h3>
                </div>
                <div class="p-6 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-amber-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Pending</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                New orders are automatically marked as Pending. These orders need to be packed, shipped, and marked as completed.
                            </p>
                        </div>
                        
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-green-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-check"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Completed</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                Mark orders as completed once they've been shipped. This will notify the customer that their order is on the way.
                            </p>
                        </div>
                        
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-red-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-times"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Cancelled</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                Orders may be cancelled by customers or by admins if products are out of stock. Cancelled orders will not be processed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Admin Footer -->
    <footer class="wooden-footer mt-auto relative overflow-hidden">
        <div class="wooden-texture-footer absolute inset-0 z-0"></div>
        <div class="container mx-auto px-4 py-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <p>&copy; 2023 PC Hardware Store Admin Panel</p>
                </div>
                <div class="mt-2 md:mt-0 flex items-center">
                    <a href="../index.php" class="footer-link mr-4">
                        <i class="fas fa-store mr-1"></i> View Store
                    </a>
                    <a href="../logout.php" class="footer-link">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    $(document).ready(function() {
        // Add wood texture to cards
        $('.wood-card').each(function() {
            if (!$(this).find('.wooden-texture-footer').length) {
                $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
            }
        });
        
        // Animate the dashboard cards
        $('.wood-card').addClass('fade-in');
        
        // Add hover effects to buttons
        $('.wooden-cart-button').hover(
            function() {
                $(this).find('i').animate({ marginRight: '8px' }, 200);
            },
            function() {
                $(this).find('i').animate({ marginRight: '0.5rem' }, 200);
            }
        );
    });
    </script>
</body>
</html>