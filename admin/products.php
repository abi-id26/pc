<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
requireAdmin();
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit();
}
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - PC Hardware Store</title>
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
                    Products
                </div>
            </div>
            <div class="flex justify-between items-center mb-6">
                <h1 class="page-title text-2xl">Products Management</h1>
                <a href="add_product.php" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-amber-800">
                    <i class="fas fa-plus mr-2  text-black"></i>
                    <span>Add Product</span>
                </a>
            </div>
            <div class="wood-card overflow-hidden rounded-lg shadow-md mb-8">
                <div class="wooden-texture-overlay"></div>
                <div class="p-6 relative z-10">
                    <div class="overflow-x-auto">
                        <table class="wood-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">ID</th>
                                    <th class="text-left">Image</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Category</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['id'] ?></td>
                                        <td>
                                            <img src="../assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-md border border-amber-200" width="60">
                                        </td>
                                        <td class="font-medium text-amber-900"><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['category']) ?></td>
                                        <td class="text-right font-bold text-amber-900">$<?= number_format($product['price'], 2) ?></td>
                                        <td class="text-center">
                                            <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?=
                                                ($product['stock'] > 10) ? 'bg-green-600' :
                                                (($product['stock'] > 0) ? 'bg-amber-600' : 'bg-red-600')
                                            ?>">
                                                <?= $product['stock'] ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="edit_product.php?id=<?= $product['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-amber-700 text-black">
                                                    <i class="fas fa-edit mr-1 text-black"></i> Edit
                                                </a>
                                                <a href="products.php?delete=<?= $product['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-red-600 text-black" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash mr-1 text-black"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-amber-800">No products found. <a href="add_product.php" class="text-amber-600 underline">Add your first product</a>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Product Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="wood-card overflow-hidden rounded-lg shadow-md">
                    <div class="wooden-texture-overlay"></div>
                    <div class="p-4 bg-amber-800 text-white">
                        <h3 class="text-lg font-bold mb-0 flex items-center text-black">
                            <i class="fas fa-tags mr-2 text-black"></i> Product Categories
                        </h3>
                    </div>
                    <div class="p-6 relative z-10">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <a href="products.php?category=CPU" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-microchip"></i>
                                </span>
                                CPUs
                            </a>
                            <a href="products.php?category=GPU" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-tv"></i>
                                </span>
                                GPUs
                            </a>
                            <a href="products.php?category=RAM" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-memory"></i>
                                </span>
                                RAM
                            </a>
                            <a href="products.php?category=Storage" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-hdd"></i>
                                </span>
                                Storage
                            </a>
                            <a href="products.php?category=Motherboard" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-server"></i>
                                </span>
                                Motherboards
                            </a>
                            <a href="products.php?category=Peripherals" class="wood-nav-link flex items-center py-2 px-3 rounded hover:bg-amber-100 transition-colors">
                                <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-2">
                                    <i class="fas fa-keyboard"></i>
                                </span>
                                Peripherals
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Quick Stats -->
                <div class="wood-card overflow-hidden rounded-lg shadow-md">
                    <div class="wooden-texture-overlay"></div>
                    <div class="p-4 bg-amber-800 text-white">
                        <h3 class="text-lg font-bold mb-0 flex items-center text-black">
                            <i class="fas fa-chart-bar mr-2 text-black"></i> Inventory Stats
                        </h3>
                    </div>
                    <div class="p-6 relative z-10">
                        <?php
                        $totalProducts = count($products);
                        $totalValue = 0;
                        $lowStockItems = 0;
                        foreach ($products as $product) {
                            $totalValue += $product['price'] * $product['stock'];
                            if ($product['stock'] <= 5) {
                                $lowStockItems++;
                            }
                        }
                        $cats = [];
                        foreach ($products as $product) {
                            if (!isset($cats[$product['category']])) {
                                $cats[$product['category']] = 0;
                            }
                            $cats[$product['category']]++;
                        }
                        $categoryCount = count($cats);
                        ?>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center bg-amber-50 p-3 rounded-lg border border-amber-200">
                                <p class="text-amber-800 mb-1">Total Products</p>
                                <p class="text-3xl font-bold text-amber-900"><?= $totalProducts ?></p>
                            </div>
                            <div class="text-center bg-amber-50 p-3 rounded-lg border border-amber-200">
                                <p class="text-amber-800 mb-1">Categories</p>
                                <p class="text-3xl font-bold text-amber-900"><?= $categoryCount ?></p>
                            </div>
                            <div class="text-center bg-amber-50 p-3 rounded-lg border border-amber-200">
                                <p class="text-amber-800 mb-1">Low Stock</p>
                                <p class="text-3xl font-bold text-red-600"><?= $lowStockItems ?></p>
                            </div>
                            <div class="text-center bg-amber-50 p-3 rounded-lg border border-amber-200">
                                <p class="text-amber-800 mb-1">Inventory Value</p>
                                <p class="text-2xl font-bold text-green-600">$<?= number_format($totalValue, 2) ?></p>
                            </div>
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