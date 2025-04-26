<?php
require_once 'includes/auth.php'; require_once '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {session_start();}
requireAdmin();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$completedOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();
$cancelledOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'cancelled'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?: 0;
$lowStockItems = $pdo->query("SELECT id, name, stock FROM products WHERE stock <= 5 ORDER BY stock ASC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recentOrders = $pdo->query("
    SELECT o.id, o.created_at, o.status, o.total, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
$monthlySales = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $months[] = date('M', strtotime("-$i months"));
    $stmt = $pdo->prepare("
        SELECT SUM(total) FROM orders
        WHERE status != 'cancelled'
        AND DATE_FORMAT(created_at, '%Y-%m') = ?
    ");
    $stmt->execute([$month]);
    $monthlySales[] = (float) ($stmt->fetchColumn() ?: 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PC Hardware Store</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS - Use the same style.css as the main site -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="flex flex-col min-h-screen bg-amber-50">
    <!-- Admin Header - Styled like the main site header but with admin context -->
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
                    Dashboard
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h1 class="page-title text-2xl font-bold mb-2 md:mb-0">Admin Dashboard</h1>
                <div class="flex space-x-2">
                    <a href="add_product.php" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-green-600 text-white">
                        <i class="fas fa-plus-circle mr-2"></i> New Product
                    </a>
                </div>
            </div>
            <!-- Key Statistics Row 1-->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Revenue -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-green-600"></div>
                    <div class="p-5 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Revenue</h5>
                            <span class="bg-green-100 p-2 rounded-full text-green-800">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-green-600">$<?= number_format($totalRevenue, 2) ?></p>
                        <p class="text-sm text-amber-700 mt-1">From active orders</p>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-600"></div>
                    <div class="p-5 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Orders</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-clipboard-list"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-800"><?= $totalOrders ?></p>
                        <div class="flex text-xs mt-1 text-amber-700 space-x-2">
                            <span><i class="fas fa-circle text-amber-600"></i> <?= $pendingOrders ?> pending</span>
                            <span><i class="fas fa-circle text-green-600"></i> <?= $completedOrders ?> completed</span>
                            <span><i class="fas fa-circle text-red-600"></i> <?= $cancelledOrders ?> cancelled</span>
                        </div>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-blue-600"></div>
                    <div class="p-5 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Products</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-800"><?= $totalProducts ?></p>
                        <div class="text-sm mt-1">
                            <a href="products.php" class="text-amber-700 hover:text-amber-900 transition-colors flex items-center">
                                <span>Manage Products</span>
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Total Users -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-purple-600"></div>
                    <div class="p-5 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Users</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-800"><?= $totalUsers ?></p>
                        <div class="text-sm mt-1">
                            <a href="users.php" class="text-amber-700 hover:text-amber-900 transition-colors flex items-center">
                                <span>Manage Users</span>
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Chart & Low Stock -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Sales Chart -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md lg:col-span-2">
                    <div class="wooden-texture-overlay"></div>
                    <div class="p-4 bg-amber-800 text-white">
                        <h4 class="text-lg font-bold mb-0 flex items-center">
                            <i class="fas fa-chart-line mr-2"></i> Sales Overview
                        </h4>
                    </div>
                    <div class="p-5 relative z-10" style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <!-- Low Stock Alerts -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md">
                    <div class="wooden-texture-overlay"></div>
                    <div class="p-4 bg-amber-800 text-white">
                        <h4 class="text-lg font-bold mb-0 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock Alerts
                        </h4>
                    </div>
                    <div class="p-5 relative z-10">
                        <?php if (count($lowStockItems) > 0): ?>
                            <ul class="divide-y divide-amber-200">
                                <?php foreach ($lowStockItems as $item): ?>
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-amber-900"><?= htmlspecialchars($item['name']) ?></p>
                                            <span class="inline-block px-2 py-1 bg-<?= $item['stock'] <= 2 ? 'red' : 'amber' ?>-100 text-<?= $item['stock'] <= 2 ? 'red' : 'amber' ?>-800 text-xs rounded">
                                                <?= $item['stock'] ?> in stock
                                            </span>
                                        </div>
                                        <a href="edit_product.php?id=<?= $item['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 text-sm">
                                            <i class="fas fa-plus mr-1"></i> Restock
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center py-6 text-amber-700">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                                <p>All products have sufficient stock</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Recent Orders Section -->
            <div class="wood-card overflow-hidden rounded-lg shadow-md mb-6">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h4 class="text-lg font-bold mb-0 flex items-center">
                        <i class="fas fa-clock mr-2"></i> Recent Orders
                    </h4>
                </div>
                <div class="p-5 relative z-10">
                    <div class="overflow-x-auto">
                        <table class="wood-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Order ID</th>
                                    <th class="text-left">Customer</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($recentOrders) > 0): ?>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td class="font-medium">?></td>
                                        <td>
                                            <div class="font-medium text-amber-900"><?= htmlspecialchars($order['username']) ?></div>
                                            <div class="text-xs text-amber-700"><?= htmlspecialchars($order['email']) ?></div>
                                        </td>
                                        <td><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?=
                                                ($order['status'] == 'completed') ? 'bg-green-600' :
                                                (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                            ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-right font-bold text-amber-900">$<?= number_format($order['total'], 2) ?></td>
                                        <td class="text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="order_detail.php?id=<?= $order['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-amber-700 text-black text-sm">
                                                    <i class="fas fa-eye mr-1"></i> View
                                                </a>
                                                <?php if ($order['status'] == 'pending'): ?>
                                                <a href="update_order.php?id=<?= $order['id'] ?>&status=completed"
                                                   class="wooden-cart-button inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm">
                                                    <i class="fas fa-check mr-1"></i> Complete
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-amber-800">No orders found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="orders.php" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-amber-800 text-black">
                            <span>View All Orders</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Quick Actions Section -->
            <div class="wood-card overflow-hidden rounded-lg shadow-md">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h4 class="text-lg font-bold mb-0 flex items-center">
                        <i class="fas fa-bolt mr-2"></i> Quick Actions
                    </h4>
                </div>
                <div class="p-5 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="add_product.php" class="wooden-cart-button flex items-center justify-center py-3 px-4">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Add New Product</span>
                        </a>
                        <a href="orders.php?status=pending" class="wooden-cart-button flex items-center justify-center py-3 px-4">
                            <i class="fas fa-truck-loading mr-2"></i>
                            <span>View Pending Orders</span>
                        </a>
                        <a href="print_order.php?id=<?= $recentOrders[0]['id'] ?? 0 ?>" target="_blank" class="wooden-cart-button flex items-center justify-center py-3 px-4">
                            <i class="fas fa-print mr-2"></i>
                            <span>Print Latest Order</span>
                        </a>
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
        $('.wood-card').each(function() {
            if (!$(this).find('.wooden-texture-footer').length) {
                $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
            }
        });
        $('.wood-card').addClass('fade-in');
        $('.wooden-cart-button').hover(
            function() {
                $(this).find('i').animate({ marginRight: '8px' }, 200);
            },
            function() {
                $(this).find('i').animate({ marginRight: '0.5rem' }, 200);
            }
        );
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'Monthly Revenue ($)',
                    data: <?= json_encode($monthlySales) ?>,
                    backgroundColor: 'rgba(180, 83, 9, 0.5)',
                    borderColor: 'rgba(180, 83, 9, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
</body>
</html>