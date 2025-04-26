<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$stmt = $pdo->prepare("
    SELECT
        o.id,
        o.total,
        o.created_at,
        o.status,
        COUNT(oi.id) AS item_count,
        GROUP_CONCAT(p.name SEPARATOR ' | ') AS product_names
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item">
            <a href="my_account.php">My Account</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Order History
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Account Sidebar -->
        <div class="w-full md:w-1/4">
            <div class="wood-card overflow-hidden">
                <div class="wooden-texture-overlay"></div>
                <div class="p-5 text-center relative z-10">
                    <div class="mb-4 mx-auto">
                        <span class="bg-amber-800 rounded-full p-4 text-white inline-flex items-center justify-center">
                            <i class="fas fa-user fa-2x"></i>
                        </span>
                    </div>
                    <h5 class="text-xl font-bold text-amber-900 mb-1">My Account</h5>
                    <hr class="my-4 border-amber-200">
                    <ul class="nav flex-col space-y-2">
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-amber-100 transition-colors rounded" href="my_account.php">
                                <i class="fas fa-user mr-2"></i> Account Overview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wooden-cart-button w-full py-2 px-4 flex items-center" href="order_history.php">
                                <i class="fas fa-history mr-2"></i> Order History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-amber-100 transition-colors rounded" href="edit_account.php">
                                <i class="fas fa-cog mr-2"></i> Account Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-red-100 text-red-700 transition-colors rounded" href="logout.php">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="w-full md:w-3/4">
            <div class="wood-card overflow-hidden">
                <div class="wooden-texture-overlay"></div>
                <div class="relative z-10">
                    <div class="p-4 bg-amber-800 text-white">
                        <h4 class="text-xl font-bold mb-0">Your Order History</h4>
                    </div>
                    <div class="p-5">
    <?php if (empty($orders)): ?>
                            <div class="p-4 bg-amber-50 text-amber-800 rounded border border-amber-200">
                                You haven't placed any orders yet. <a href="index.php" class="text-amber-900 underline">Browse products</a>.
        </div>
    <?php else: ?>
                            <div class="p-4 bg-amber-50 text-amber-800 rounded border border-amber-200 mb-4">
                                <p><strong>Order Status Guide:</strong></p>
                                <ul class="mt-2 list-disc pl-5 space-y-1">
                                    <li><span class="inline-block px-2 py-1 rounded text-white text-xs bg-amber-600">Pending</span> - Your order is being prepared. Orders in this status can be cancelled.</li>
                                    <li><span class="inline-block px-2 py-1 rounded text-white text-xs bg-green-600">Completed</span> - Your order has been shipped or delivered.</li>
                                    <li><span class="inline-block px-2 py-1 rounded text-white text-xs bg-red-600">Cancelled</span> - This order has been cancelled and will not be processed.</li>
                                </ul>
                            </div>
                            <div class="flex flex-col space-y-4">
            <?php foreach ($orders as $order): ?>
                                    <a href="order_details.php?id=<?= $order['id'] ?>" class="wood-card p-4 hover:bg-amber-50 transition-colors block">
                                        <div class="flex flex-col md:flex-row justify-between">
                        <div>
                                                <h5 class="text-lg font-bold text-amber-900 mb-1">Order ?></h5>
                                                <div class="text-amber-700 text-sm mb-2">
                                <?= date('M j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                                                </div>
                                                <div class="mt-2 flex flex-wrap gap-2 items-center">
                                                    <span class="inline-block px-2 py-1 rounded text-white text-xs <?=
                                                        ($order['status'] == 'cancelled') ? 'bg-red-600' :
                                                        (($order['status'] == 'completed') ? 'bg-green-600' : 'bg-amber-600')
                                ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                                                    <span class="text-amber-700 text-sm">
                                    <?= $order['item_count'] ?> item(s)
                                </span>
                            </div>
                                                <div class="mt-2 text-sm text-amber-600 line-clamp-1">
                                                    <?= htmlspecialchars($order['product_names']) ?>
                                                </div>
                                            </div>
                                            <div class="mt-3 md:mt-0 text-right">
                                                <div class="text-xl font-bold text-amber-900">$<?= number_format($order['total'], 2) ?></div>
                                                <div class="text-amber-700 text-sm mt-1 flex items-center justify-end">
                                                    View details <i class="fas fa-arrow-right ml-1"></i>
                        </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>