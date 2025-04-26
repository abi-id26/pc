<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Get category filter and search query if set
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

// Pagination settings
$productsPerPage = 8; // Number of products per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $productsPerPage;

// Build count query to get total results
$countQuery = "SELECT COUNT(*) as total FROM products WHERE 1=1";
$countParams = [];

if ($category) {
    $countQuery .= " AND category = :category";
    $countParams[':category'] = $category;
}

if ($search) {
    $countQuery .= " AND (name LIKE :search OR description LIKE :search)";
    $countParams[':search'] = "%$search%";
}

$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($countParams);
$totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

// Build product query with pagination
$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($category) {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

if ($search) {
    $query .= " AND (name LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);

// Bind parameters
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

// Bind limit and offset as integers
$stmt->bindValue(':limit', $productsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* Products animations */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .wooden-texture-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('assets/images/wood-texture.jpg');
        background-size: cover;
        opacity: 0.08;
        pointer-events: none;
    }

    /* Welcome section styles */
    .welcome-section {
        position: relative;
        background-color: #f8f0e3;
        border-radius: 0.5rem;
        border: 1px solid #d2b48c;
        box-shadow: 0 4px 6px rgba(139, 69, 19, 0.1);
        overflow: hidden;
    }

    .welcome-section h2 {
        text-shadow: 1px 1px 2px rgba(139, 69, 19, 0.2);
    }
    
    .welcome-section .wooden-cart-button {
        background-color: #8b4513;
        color: #fff;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-section .wooden-cart-button:hover {
        background-color: #a0522d;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(139, 69, 19, 0.2);
    }
    
    .welcome-section .wooden-cart-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('assets/images/wood-texture.jpg');
        background-size: cover;
        opacity: 0.2;
        pointer-events: none;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <?php if ($category): ?>
        <div class="wood-breadcrumb-item">
            <a href="index.php#products">Products</a>
        </div>
        <div class="wood-breadcrumb-item active">
            <?= htmlspecialchars($category) ?>
        </div>
        <?php else: ?>
        <div class="wood-breadcrumb-item active">
            Products
        </div>
                    <?php endif; ?>
    </div>

    <div class="wood-card welcome-section mb-8 p-6">
        <div class="wooden-texture-overlay"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-3 text-amber-900">Welcome to PC Hardware Store</h2>
            <p class="text-amber-800 mb-4">Your trusted destination for premium computer hardware and components. Browse our extensive collection of CPUs, GPUs, RAM, storage solutions, and more to build your dream PC setup.</p>
            <div class="flex flex-wrap gap-4 mt-2">
                <a href="index.php#products" class="wooden-cart-button px-4 py-2 inline-flex items-center">
                    <i class="fas fa-shopping-basket mr-2 text-black"></i>
                    <span class="button-text">Browse Products</span>
                </a>
                <a href="build_pc.php" class="wooden-cart-button px-4 py-2 inline-flex items-center">
                    <i class="fas fa-tools mr-2 text-black"></i>
                    <span class="button-text">Build your own PC</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="page-title text-xl mb-4">Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="index.php?category=CPU#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-microchip text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">CPUs</h3>
                </div>
            </a>
            <a href="index.php?category=GPU#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-tv text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">GPUs</h3>
                </div>
            </a>
            <a href="index.php?category=RAM#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-memory text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">RAM</h3>
                </div>
            </a>
            <a href="index.php?category=Storage#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-hdd text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Storage</h3>
                </div>
            </a>
            <a href="index.php?category=Motherboard#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-server text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Motherboards</h3>
                </div>
            </a>
            <a href="index.php?category=Power Supply#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-plug text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Power Supplies</h3>
                </div>
            </a>
            <a href="index.php?category=CPU Cooler#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-wind text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Cooling</h3>
                </div>
            </a>
            <a href="index.php?category=Case#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-desktop text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Cases</h3>
                </div>
            </a>
            <a href="index.php?category=Monitor#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-display text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Monitors</h3>
                </div>
            </a>
            <a href="index.php?category=Peripherals#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-keyboard text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Peripherals</h3>
                </div>
            </a>
            <a href="index.php?category=Networking#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-network-wired text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Networking</h3>
                </div>
            </a>
            <a href="index.php?category=Accessories#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-headset text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Accessories</h3>
                </div>
            </a>
        </div>
    </div>

    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h1 id="products" class="page-title text-2xl font-bold"><?= $category ? $category . ' Products' : 'All Products' ?></h1>
                <?php if ($search): ?>
                <p class="text-gray-600 italic">Search results for: "<?= htmlspecialchars($search) ?>"</p>
                <?php endif; ?>
            </div>
            <div class="mt-4 md:mt-0 w-full md:w-auto">
                <form method="get" class="flex" action="index.php#products">
                        <?php if ($category): ?>
                            <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                        <?php endif; ?>
                    <div class="relative flex w-full md:w-96">
                        <input type="text" class="wood-input w-full px-4 py-2 rounded-l-md text-lg" 
                               placeholder="Search products..." name="search" value="<?= htmlspecialchars($search ?? '') ?>">
                        <button class="wooden-cart-button px-6 py-2 rounded-r-md text-lg" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="wood-card p-4 text-center">
            <div class="card-content">
                <p class="wood-card-text">No products found matching your criteria.</p>
                <a href="index.php#products" class="wooden-cart-button mt-3 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="button-text">Browse All Products</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="wood-card animated-card product-card">
                    <div class="p-4">
                        <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" class="mx-auto product-image" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                    <div class="p-4 card-content">
                        <h5 class="wood-card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="wood-card-text text-truncate-2 h-12"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="wood-price">$<?= number_format($product['price'], 2) ?></span>
                            <span class="wood-badge"><?= htmlspecialchars($product['category']) ?></span>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-t border-amber-100">
                        <form action="add_to_cart.php" method="post" class="flex justify-between">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" class="wood-input w-16 px-2 py-1 rounded" aria-label="Quantity">
                            <button type="submit" class="wooden-cart-button add-to-cart-btn flex-grow ml-2">
                                <svg viewBox="0 0 24 24">
                                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A.996.996 0 0 0 21.42 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path>
                                </svg>
                                <span class="button-text">Add to Cart</span>
                                </button>
                            </form>
                        <a href="product.php?id=<?= $product['id'] ?>" class="wooden-cart-button block text-center mt-3">
                            <i class="fas fa-eye mr-2"></i>
                            <span class="button-text">View Details</span>
                            </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="wood-pagination mt-10">
            <?php if ($currentPage > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>#products" class="wood-page-link"><i class="fas fa-chevron-left"></i></a>
            <?php else: ?>
                <span class="wood-page-link disabled"><i class="fas fa-chevron-left"></i></span>
            <?php endif; ?>
            
            <?php 
            // Show appropriate page numbers
            $startPage = max(1, min($currentPage - 1, $totalPages - 4));
            $endPage = min($totalPages, max(5, $currentPage + 1));
            
            for ($i = $startPage; $i <= $endPage; $i++): 
            ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>#products" 
                   class="wood-page-link <?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>#products" class="wood-page-link"><i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
                <span class="wood-page-link disabled"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
            
            <span class="ml-3 text-sm text-amber-900">
                Page <?= $currentPage ?> of <?= $totalPages ?> (<?= $totalProducts ?> products)
            </span>
        </div>
    <?php endif; ?>
    
    <div class="wooden-divider my-10"></div>
    
    <div class="mb-10">
        <h2 class="page-title text-xl">Popular Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <a href="index.php?category=CPU#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-microchip text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">CPUs</h3>
                </div>
            </a>
            <a href="index.php?category=GPU#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-tv text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">GPUs</h3>
                </div>
            </a>
            <a href="index.php?category=RAM#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-memory text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">RAM</h3>
                </div>
            </a>
            <a href="index.php?category=Storage#products" class="wood-card p-4 text-center hover:scale-105 transition-transform">
                <div class="card-content">
                    <i class="fas fa-hdd text-4xl text-amber-800 mb-3"></i>
                    <h3 class="wood-card-title">Storage</h3>
                </div>
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Add wood texture to cards
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0" style="background-image:url(\'assets/images/wood-texture.png\')"></div>');
    });
    
    // Animate product cards on page load
    $('.product-card').each(function(index) {
        $(this).addClass('opacity-0').css({
            'animation': 'none',
            'transform': 'translateY(20px)'
        });
        
        let $card = $(this);
        setTimeout(function() {
            $card.animate({
                opacity: 1,
                transform: 'translateY(0)'
            }, 400 + (index * 100), 'easeOutCubic');
        }, 100 + (index * 50));
    });
    
    // Enhanced hover effect for product cards
    $('.product-card').hover(
        function() {
            $(this).find('img').animate({ transform: 'scale(1.05)' }, 300);
            $(this).find('.wood-card-title').css('color', '#8B4513');
        },
        function() {
            $(this).find('img').animate({ transform: 'scale(1)' }, 300);
            $(this).find('.wood-card-title').css('color', '#5c4033');
        }
    );
    
    // Add to cart button click effect
    $('.add-to-cart-btn').click(function() {
        $(this).closest('.product-card').addClass('added-to-cart')
            .delay(300).queue(function(){
                $(this).removeClass('added-to-cart').dequeue();
            });
        
        // Add a small bounce to the SVG icon
        $(this).find('svg').animate(
            { transform: 'translateZ(20px) scale(1.3)' }, 
            100, 
            function() {
                $(this).animate({ transform: 'translateZ(10px) scale(1)' }, 200);
            }
        );
    });
    
    // Add hover effect to payment icons
    $('.payment-icon').hover(
        function() {
            $(this).addClass('payment-icon-hover');
        },
        function() {
            $(this).removeClass('payment-icon-hover');
        }
    );
});
</script>