<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit();
}
?>

<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item">
            <a href="index.php?category=<?= urlencode($product['category']) ?>"><?= htmlspecialchars($product['category']) ?></a>
        </div>
        <div class="wood-breadcrumb-item active">
            <?= htmlspecialchars($product['name']) ?>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <!-- Product Image -->
        <div class="w-full md:w-1/2 px-4 mb-6 md:mb-0">
            <div class="wood-card p-4">
                <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" class="w-full object-contain h-80" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="w-full md:w-1/2 px-4">
            <div class="wood-card p-6">
                <div class="card-content">
                    <h1 class="text-3xl font-bold text-wood-dark mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="mb-4">
                        <span class="wood-badge"><?= htmlspecialchars($product['category']) ?></span>
                    </div>
                    
                    <h3 class="wood-price text-2xl mb-6">$<?= number_format($product['price'], 2) ?></h3>
                    
                    <div class="wooden-divider"></div>
                    
                    <div class="mb-6">
                        <h5 class="wood-card-title">Description</h5>
                        <p class="wood-card-text"><?= htmlspecialchars($product['description']) ?></p>
                    </div>
                    
                    <form action="add_to_cart.php" method="post" class="mb-6">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="wood-input-group">
                                <label for="quantity" class="wood-label">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" class="wood-input w-20">
                            </div>
                            <button type="submit" class="wooden-cart-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span class="button-text">Add to Cart</span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="wooden-divider"></div>
                    
                    <div class="wood-card bg-opacity-50 p-4 mt-4">
                        <h5 class="wood-card-title">Product Details</h5>
                        <ul class="space-y-2 mt-4">
                            <li class="flex items-center py-2 border-b border-wood-tan">
                                <strong class="w-1/3 text-wood-dark">Category:</strong> 
                                <span><?= htmlspecialchars($product['category']) ?></span>
                            </li>
                            <li class="flex items-center py-2">
                                <strong class="w-1/3 text-wood-dark">Availability:</strong> 
                                <span><?= $product['stock'] > 0 ? '<span class="text-green-600">In Stock</span>' : '<span class="text-red-600">Out of Stock</span>' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <div class="mt-12">
        <h3 class="page-title text-2xl mb-6">Related Products</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
            $stmt->execute([$product['category'], $product['id']]);
            $relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($relatedProducts as $related): ?>
                <div class="wood-card animated-card">
                    <div class="card-content p-4">
                        <img src="assets/images/products/<?= htmlspecialchars($related['image']) ?>" class="w-full h-48 object-contain mb-4" alt="<?= htmlspecialchars($related['name']) ?>">
                        <h5 class="wood-card-title text-truncate-2"><?= htmlspecialchars($related['name']) ?></h5>
                        <p class="wood-price mb-4">$<?= number_format($related['price'], 2) ?></p>
                        <a href="product.php?id=<?= $related['id'] ?>" class="wooden-cart-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <span class="button-text">View Details</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Add animation to product cards
        $('.animated-card').hover(
            function() {
                $(this).addClass('shadow-hover');
            },
            function() {
                $(this).removeClass('shadow-hover');
            }
        );
        
        // Add animation to the add to cart button
        $('.wooden-cart-button').click(function() {
            $(this).addClass('added-to-cart');
            setTimeout(function() {
                $('.wooden-cart-button').removeClass('added-to-cart');
            }, 500);
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>