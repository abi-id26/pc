<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's orders (last 5)
$stmt = $pdo->prepare("
    SELECT id, total, created_at, status 
    FROM orders 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            My Account
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
                    <h5 class="text-xl font-bold text-amber-900 mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="text-amber-700">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                    <hr class="my-4 border-amber-200">
                    <ul class="nav flex-col space-y-2">
                        <li class="nav-item">
                            <a class="wooden-cart-button w-full py-2 px-4 flex items-center" href="my_account.php">
                                <i class="fas fa-user mr-2"></i> Account Overview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-amber-100 transition-colors rounded" href="order_history.php">
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
                        <h4 class="text-xl font-bold mb-0">Account Overview</h4>
                    </div>
                    <div class="p-5">
                        <!-- Personal Info -->
                        <div class="mb-6">
                            <h5 class="flex items-center text-lg text-amber-900"><i class="fas fa-id-card mr-2"></i> Personal Information</h5>
                            <hr class="my-3 border-amber-200">
                            <div class="flex flex-col md:flex-row justify-between">
                                <div class="mb-4 md:mb-0">
                                    <p class="mb-2"><strong class="text-amber-800">Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                                    <p><strong class="text-amber-800">Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                                </div>
                                <div>
                                    <a href="edit_account.php" class="wooden-cart-button px-4 py-2 inline-flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Edit Information
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        <div>
                            <h5 class="flex items-center text-lg text-amber-900"><i class="fas fa-shopping-bag mr-2"></i> Recent Orders</h5>
                            <hr class="my-3 border-amber-200">
                            <?php if (empty($orders)): ?>
                                <div class="p-4 bg-amber-50 text-amber-800 rounded border border-amber-200">No orders found.</div>
                            <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead>
                                            <tr class="bg-amber-50">
                                                <th class="px-4 py-2 text-left text-amber-800">Order #</th>
                                                <th class="px-4 py-2 text-left text-amber-800">Date</th>
                                                <th class="px-4 py-2 text-left text-amber-800">Total</th>
                                                <th class="px-4 py-2 text-left text-amber-800">Status</th>
                                                <th class="px-4 py-2 text-amber-800">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr class="border-b border-amber-100">
                                                    <td class="px-4 py-3"><?= $order['id'] ?></td>
                                                    <td class="px-4 py-3"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                                    <td class="px-4 py-3">$<?= number_format($order['total'], 2) ?></td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-block px-2 py-1 rounded text-white text-xs <?= 
                                                            ($order['status'] == 'completed') ? 'bg-green-600' : 
                                                            (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                                        ?>">
                                                            <?= ucfirst($order['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <a href="order_details.php?id=<?= $order['id'] ?>" class="wooden-cart-button px-3 py-1 inline-block text-sm">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 text-right">
                                    <a href="order_history.php" class="wooden-cart-button px-4 py-2 inline-flex items-center">
                                        View Full Order History <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>