<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
requireAdmin();
$stmt = $pdo->prepare("
    SELECT * FROM users
    ORDER BY created_at DESC
");
$stmt->execute();
$users = $stmt->fetchAll();
$totalUsers = count($users);
$adminCount = 0;
$customerCount = 0;
$recentUsers = 0;
$thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
foreach ($users as $user) {
    if ($user['role'] == 'admin') {
        $adminCount++;
    } else {
        $customerCount++;
    }
    if (strtotime($user['created_at']) > strtotime($thirtyDaysAgo)) {
        $recentUsers++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - PC Hardware Store</title>
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
                    Users
                </div>
            </div>
            <!-- User Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Total Users</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-users text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-800"><?= $totalUsers ?></p>
                    </div>
                </div>
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-amber-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Customers</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-user text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-amber-600"><?= $customerCount ?></p>
                    </div>
                </div>
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-blue-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">Admins</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-user-shield text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-blue-600"><?= $adminCount ?></p>
                    </div>
                </div>
                <div class="wood-card overflow-hidden rounded-lg shadow-md relative">
                    <div class="wooden-texture-overlay"></div>
                    <div class="absolute top-0 left-0 w-full h-1 bg-green-600"></div>
                    <div class="p-4 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-md font-semibold text-amber-900">New (30 days)</h5>
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800">
                                <i class="fas fa-user-plus text-black"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-green-600"><?= $recentUsers ?></p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center mb-6">
                <h1 class="page-title text-2xl">User Management</h1>
                <div class="flex space-x-2">
                    <a href="add_user.php" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-green-600 text-black">
                        <i class="fas fa-user-plus mr-2 text-black"></i> Add User
                    </a>
                    <a href="users.php?type=admin" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-blue-600 text-black">
                        <i class="fas fa-user-shield mr-2 text-black"></i> Admins
                    </a>
                    <a href="users.php?type=customer" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-amber-700 text-black">
                        <i class="fas fa-user mr-2 text-black"></i> Customers
                    </a>
                    <a href="users.php" class="wooden-cart-button inline-flex items-center px-3 py-2 bg-amber-800 text-black">
                        <i class="fas fa-list-ul mr-2 text-black"></i> All Users
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
                                    <th class="text-left">ID</th>
                                    <th class="text-left">Username</th>
                                    <th class="text-left">Email</th>
                                    <th class="text-left">Joined</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($users) > 0): ?>
                                    <?php
                                    $filteredUsers = $users;
                                    if (isset($_GET['type']) && $_GET['type']) {
                                        $roleType = ($_GET['type'] == 'admin') ? 'admin' : 'user';
                                        $filteredUsers = array_filter($users, function($user) use ($roleType) {
                                            return $user['role'] == $roleType;
                                        });
                                    }
                                    if (count($filteredUsers) > 0):
                                        foreach ($filteredUsers as $user):
                                            $stmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?");
                                            $stmt->execute([$user['id']]);
                                            $orderData = $stmt->fetch();
                                            $orderCount = $orderData ? $orderData['order_count'] : 0;
                                    ?>
                                    <tr>
                                        <td class="font-medium">?></td>
                                        <td>
                                            <div class="font-medium text-amber-900"><?= htmlspecialchars($user['username']) ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                                        <td class="text-center">
                                            <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?=
                                                ($user['role'] == 'admin') ? 'bg-blue-600' : 'bg-amber-600'
                                            ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center font-bold">
                                            <?php if ($orderCount > 0): ?>
                                                <a href="orders.php?user_id=<?= $user['id'] ?>" class="hover:text-amber-700 transition-colors">
                                                    <?= $orderCount ?>
                                                </a>
                                            <?php else: ?>
                                                0
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-amber-700 text-black">
                                                    <i class="fas fa-edit mr-1 text-black"></i> Edit
                                                </a>
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                    <a href="toggle_admin.php?id=<?= $user['id'] ?>"
                                                       class="wooden-cart-button inline-flex items-center px-3 py-1 <?= ($user['role'] == 'admin') ? 'bg-yellow-600' : 'bg-blue-600' ?> text-black"
                                                       onclick="return confirm('<?= ($user['role'] == 'admin') ? 'Remove admin privileges?' : 'Grant admin privileges?' ?>')">
                                                        <i class="fas <?= ($user['role'] == 'admin') ? 'fa-user' : 'fa-user-shield' ?> mr-1 text-black"></i>
                                                        <?= ($user['role'] == 'admin') ? 'Make Customer' : 'Make Admin' ?>
                                                    </a>
                                                    <a href="delete_user.php?id=<?= $user['id'] ?>"
                                                       class="wooden-cart-button inline-flex items-center px-3 py-1 bg-red-600 text-black"
                                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                        <i class="fas fa-trash mr-1 text-black"></i> Delete
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-amber-800">No users found matching the current filter.</td>
                                    </tr>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-amber-800">No users found in the database.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- User Management Guide -->
            <div class="wood-card overflow-hidden rounded-lg shadow-md">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h3 class="text-lg font-bold mb-0 flex items-center text-black">
                        <i class="fas fa-info-circle mr-2 text-black"></i> User Management Guide
                    </h3>
                </div>
                <div class="p-6 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-amber-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-user"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Customers</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                Regular users who can browse products, place orders, and manage their own account information.
                            </p>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-blue-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Administrators</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                Admins can manage products, process orders, and have access to the admin panel. Be careful when assigning admin privileges.
                            </p>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="flex items-center mb-2">
                                <span class="bg-red-600 p-2 rounded-full text-white mr-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <h4 class="font-bold text-amber-900">Warning</h4>
                            </div>
                            <p class="text-amber-800 text-sm">
                                Deleting users cannot be undone. Users with orders will have their order history preserved but no longer be able to log in.
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
    });
    </script>
</body>
</html>