<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Initialize cart session if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get full product details for items in cart
$cart_items = [];
$total = 0;

foreach ($_SESSION['cart'] as $id => $item) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $product['quantity'] = $item['quantity'];
        $product['subtotal'] = $product['price'] * $item['quantity'];
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Shopping Cart
        </div>
    </div>
    
    <h1 class="page-title text-2xl mb-6">Your Shopping Cart</h1>
    
    <div id="cart-message" class="hidden alert"></div>
    
    <?php if (empty($cart_items)): ?>
        <div class="wood-card p-6 text-center">
            <div class="card-content">
                <i class="fas fa-shopping-cart text-6xl text-amber-700 mb-4"></i>
                <h3 class="wood-card-title text-xl mb-4">Your cart is empty</h3>
                <p class="wood-card-text mb-6">Add items to your cart to continue shopping.</p>
                <a href="index.php" class="wooden-cart-button inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="button-text">Browse Products</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div id="cart-content" class="fade-in">
            <div class="overflow-x-auto">
                <table class="wood-table w-full mb-8">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Product</th>
                            <th class="px-6 py-3 text-left">Price</th>
                            <th class="px-6 py-3 text-left">Quantity</th>
                            <th class="px-6 py-3 text-left">Subtotal</th>
                            <th class="px-6 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr id="cart-item-<?= $item['id'] ?>" class="animated-card">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                             class="w-20 h-20 object-contain bg-amber-50 rounded-md mr-4 border border-amber-200" 
                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                        <div>
                                            <h5 class="font-medium text-amber-900"><?= htmlspecialchars($item['name']) ?></h5>
                                            <p class="text-sm text-amber-700"><?= htmlspecialchars($item['category']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-amber-900 font-medium">$<?= number_format($item['price'], 2) ?></td>
                                <td class="px-6 py-4">
                                    <div class="wood-quantity-control flex items-center">
                                        <button type="button" class="quantity-btn quantity-decrease flex items-center justify-center w-8 h-8 bg-amber-100 hover:bg-amber-200 rounded-l-md border border-amber-300">
                                            <i class="fas fa-minus text-amber-800"></i>
                                        </button>
                                        <input type="number" name="quantities[<?= $item['id'] ?>]" 
                                               value="<?= $item['quantity'] ?>" min="1" 
                                               class="wood-input-quantity text-center w-12 h-8 border-t border-b border-amber-300 z-10" 
                                               data-id="<?= $item['id'] ?>">
                                        <button type="button" class="quantity-btn quantity-increase flex items-center justify-center w-8 h-8 bg-amber-100 hover:bg-amber-200 rounded-r-md border border-amber-300">
                                            <i class="fas fa-plus text-amber-800"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-amber-900 font-bold">$<span class="item-subtotal"><?= number_format($item['subtotal'], 2) ?></span></td>
                                <td class="px-6 py-4">
                                    <button type="button" class="wood-btn-delete remove-item" data-id="<?= $item['id'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-amber-900">Total:</td>
                            <td class="px-6 py-4 font-bold text-amber-900 text-xl">$<span id="cart-total"><?= number_format($total, 2) ?></span></td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-between mt-6">
                <a href="index.php" class="wooden-cart-button mb-4 sm:mb-0 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="button-text">Continue Shopping</span>
                </a>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <button type="button" id="update-cart" class="wooden-cart-button">
                        <i class="fas fa-sync-alt mr-2"></i>
                        <span class="button-text">Update Cart</span>
                    </button>
                    <a href="checkout.php" class="wooden-cart-button bg-amber-800">
                        <i class="fas fa-shopping-bag mr-2 text-black"></i>
                        <span class="button-text text-black font-bold">Checkout</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Order Summary Card - visible on non-empty cart -->
    <?php if (!empty($cart_items)): ?>
    <div class="wooden-divider my-10"></div>
    
    <div class="wood-card mt-8 p-6 max-w-md mx-auto">
        <div class="card-content">
            <h3 class="wood-card-title text-xl mb-4">Order Summary</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between border-b border-amber-200 pb-2">
                    <span class="text-amber-800">Subtotal:</span>
                    <span class="font-medium">$<?= number_format($total, 2) ?></span>
                </div>
                
                <div class="flex justify-between border-b border-amber-200 pb-2">
                    <span class="text-amber-800">Shipping:</span>
                    <span class="font-medium">$<?= number_format(10.00, 2) ?></span>
                </div>
                
                <div class="flex justify-between border-b border-amber-200 pb-2">
                    <span class="text-amber-800">Tax:</span>
                    <span class="font-medium">$<?= number_format($total * 0.07, 2) ?></span>
                </div>
                
                <div class="flex justify-between font-bold text-lg pt-2">
                    <span class="text-amber-900">Total:</span>
                    <span class="text-amber-900">$<?= number_format($total + 10.00 + ($total * 0.07), 2) ?></span>
                </div>
            </div>
            
            <div class="wooden-divider my-6"></div>
            
            <!-- Coupon code section -->
            <div class="mt-4">
                <label for="coupon" class="wood-label">Have a coupon?</label>
                <div class="flex mt-2">
                    <input type="text" id="coupon" class="wood-input flex-grow rounded-r-none" placeholder="Enter coupon code">
                    <button class="wooden-cart-button rounded-l-none">
                        <span class="button-text">Apply</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Add wood texture to cards and table
    $('.wood-card, .wood-table').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    
    // Animate cart items on page load
    $('.animated-card').each(function(index) {
        $(this).css('opacity', 0);
        $(this).animate({
            opacity: 1
        }, 400 + (index * 100));
    });

    // Custom wood button styling for delete
    $('.wood-btn-delete').each(function() {
        $(this).css({
            'background': 'linear-gradient(to bottom, #fee2e2, #fecaca)',
            'padding': '8px 12px',
            'border-radius': '8px',
            'color': '#b91c1c',
            'border': '1px solid #f87171',
            'transition': 'all 0.3s ease',
            'position': 'relative',
            'z-index': '10'
        });
    }).hover(
        function() {
            $(this).css({
                'background': 'linear-gradient(to bottom, #fecaca, #ef4444)',
                'transform': 'translateY(-2px)',
                'box-shadow': '0 4px 8px rgba(239, 68, 68, 0.3)',
                'color': '#fff'
            });
        },
        function() {
            $(this).css({
                'background': 'linear-gradient(to bottom, #fee2e2, #fecaca)',
                'transform': 'translateY(0)',
                'box-shadow': 'none',
                'color': '#b91c1c'
            });
        }
    );
    
    // Add click handlers for quantity buttons
    $('.quantity-decrease').click(function() {
        const input = $(this).siblings('input');
        const currentValue = parseInt(input.val());
        if (currentValue > 1) {
            input.val(currentValue - 1).trigger('change');
        }
    });
    
    $('.quantity-increase').click(function() {
        const input = $(this).siblings('input');
        const currentValue = parseInt(input.val());
        input.val(currentValue + 1).trigger('change');
    });
    
    // Apply wooden styling to quantity inputs
    $('.wood-input-quantity').each(function() {
        $(this).css({
            'background-color': '#f5e0c0',
            'color': '#5c4033',
            'font-weight': 'bold',
            'outline': 'none',
            'position': 'relative',
            'z-index': '10'
        });
    }).on('focus', function() {
        $(this).css({
            'box-shadow': '0 0 0 2px rgba(176, 132, 99, 0.3)'
        });
    }).on('blur', function() {
        $(this).css({
            'box-shadow': 'none'
        });
    });

    // Remove item from cart with animation
    $('.remove-item').click(function() {
        const id = $(this).data('id');
        const row = $('#cart-item-' + id);
            
        fetch('includes/cart_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=remove&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate row removal
                row.css('background-color', '#fee2e2')
                   .animate({ opacity: 0, height: 0, padding: 0 }, 500, function() {
                       $(this).remove();
                       updateCartTotal();
                       updateCartBadge();
                       showMessage('Item removed from cart', 'success');
                       
                       if ($('tbody tr').length === 0) {
                           location.reload(); // Reload if cart is empty
                       }
                   });
            }
        })
        .catch(error => {
            showMessage('Error removing item', 'danger');
        });
    });

    // Update quantities with debounce
    let updateTimeout;
    $('[name^="quantities"]').on('change', function() {
        const input = $(this);
        
        // Visual feedback
        input.addClass('ring-2 ring-amber-400');
        
        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(() => {
            updateCart();
            updateCartBadge();
            
            // Remove visual feedback
            setTimeout(() => {
                input.removeClass('ring-2 ring-amber-400');
            }, 1000);
        }, 500);
    });

    // Fix button interaction issue by ensuring relative positioning and z-index
    $('.wooden-cart-button').css({
        'position': 'relative',
        'z-index': '10'
    });

    // Update cart button with animation
    $('#update-cart').click(function() {
        $(this).addClass('animate-pulse');
        updateCart();
    });

    function updateCart() {
        const quantities = {};
        $('[name^="quantities"]').each(function() {
            quantities[$(this).data('id')] = $(this).val();
        });

        fetch('includes/cart_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=update&quantities=${JSON.stringify(quantities)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message before reload
                showMessage('Cart updated successfully', 'success');
                
                setTimeout(() => {
                    location.reload();
                }, 800);
            }
        })
        .catch(error => {
            showMessage('Error updating cart', 'danger');
            $('#update-cart').removeClass('animate-pulse');
        });
    }

    function updateCartTotal() {
        let total = 0;
        $('.item-subtotal').each(function() {
            total += parseFloat($(this).text());
        });
        
        // Animate the total changing
        $('#cart-total').fadeOut(200, function() {
            $(this).text(total.toFixed(2)).fadeIn(200);
        });
    }

    function updateCartBadge() {
        const cartBadge = $('.fa-shopping-cart + .inline-flex');
        let totalItems = 0;
        
        $('tbody tr').each(function() {
            const quantityInput = $(this).find('[name^="quantities"]');
            totalItems += parseInt(quantityInput.val()) || 0;
        });
        
        if (cartBadge.length) {
            if (totalItems > 0) {
                cartBadge.text(totalItems);
                
                if (cartBadge.css('display') === 'none') {
                    cartBadge.css({display: 'inline-flex', scale: 0})
                             .animate({scale: 1}, 300);
                } else {
                    cartBadge.addClass('scale-up');
                    setTimeout(() => {
                        cartBadge.removeClass('scale-up');
                    }, 500);
                }
            } else {
                cartBadge.css('display', 'none');
            }
        }
    }

    function showMessage(message, type) {
        const messageDiv = $('#cart-message');
        messageDiv.removeClass('hidden bg-green-100 bg-red-100 text-green-800 text-red-800');
        
        if (type === 'success') {
            messageDiv.addClass('bg-green-100 text-green-800');
            messageDiv.html(`<i class="fas fa-check-circle mr-2"></i> ${message}`);
        } else {
            messageDiv.addClass('bg-red-100 text-red-800');
            messageDiv.html(`<i class="fas fa-exclamation-circle mr-2"></i> ${message}`);
        }
        
        messageDiv.slideDown(300).delay(3000).slideUp(300);
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>