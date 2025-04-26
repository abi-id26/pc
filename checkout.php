<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) { header("Location: cart.php"); exit(); }
$cart_items = []; $total = 0; $shipping = 10.00; $tax_rate = 0.07;
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
$tax = $total * $tax_rate;
$grand_total = $total + $shipping + $tax;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    try {
        error_log("Processing checkout. User ID: " . $_SESSION['user_id'] . ", Total: " . $grand_total);
        error_log("POST data: " . print_r($_POST, true));
        error_log("Session data: " . print_r($_SESSION, true));
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $grand_total]);
        $order_id = $pdo->lastInsertId();
        if (!$order_id) {
            throw new Exception("Failed to create order - no order ID returned");
        }
        error_log("Order created with ID: " . $order_id);
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            error_log("Added item to order: Product ID " . $item['id'] . ", Quantity " . $item['quantity']);
        }
        if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['address'])) {
            error_log("Saving shipping address for order " . $order_id);
            $stmt = $pdo->prepare("INSERT INTO shipping_addresses (order_id, first_name, last_name, address, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $order_id,
                $_POST['firstName'],
                $_POST['lastName'],
                $_POST['address'],
                $_POST['city'] ?? '',
                $_POST['state'] ?? '',
                $_POST['zip'] ?? ''
            ]);
        }
        $pdo->commit();
        error_log("Order transaction committed successfully");
        // Clear cart
        unset($_SESSION['cart']);
        $_SESSION['last_order_id'] = $order_id;
        error_log("Redirecting to order confirmation page for order " . $order_id);
        echo "<script>window.location.href = 'order_confirmation.php?id=" . $order_id . "';</script>";
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error processing order: " . $e->getMessage());
        $error = "There was an error processing your order. Please try again: " . $e->getMessage();
    }
}
require_once 'includes/header.php';
?>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item"> <a href="index.php"><i class="fas fa-home"></i> Home</a> </div>
        <div class="wood-breadcrumb-item"> <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a> </div>
        <div class="wood-breadcrumb-item active">Checkout</div>
    </div>
    <h1 class="page-title text-2xl mb-6">Secure Checkout</h1>
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column - Form -->
        <div class="w-full lg:w-2/3">
            <div class="wood-card overflow-hidden fade-in">
                <div class="bg-amber-800 py-4 px-6 border-b border-amber-700">
                    <h3 class="text-xl font-bold text-white">
                        <i class="fas fa-credit-card mr-2"></i> Checkout Information
                    </h3>
                </div>
                <div class="p-6 card-content">
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <p><i class="fas fa-exclamation-circle mr-2"></i> <?= $error ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 mb-6 rounded">
                            <p><i class="fas fa-info-circle mr-2"></i> Please <a href="login.php" class="text-amber-800 font-bold hover:underline">login</a> or <a href="register.php" class="text-amber-800 font-bold hover:underline">register</a> to complete your purchase.</p>
                        </div>
                    <?php else: ?>
                        <form method="post" id="checkout-form">
                            <h4 class="wood-card-title text-lg mb-4 pb-2 border-b border-amber-200">
                                <i class="fas fa-truck mr-2"></i> Shipping Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="firstName" class="wood-label">First Name</label>
                                    <input type="text" class="wood-input" id="firstName" name="firstName" required>
                                </div>
                                <div>
                                    <label for="lastName" class="wood-label">Last Name</label>
                                    <input type="text" class="wood-input" id="lastName" name="lastName" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="address" class="wood-label">Address</label>
                                    <input type="text" class="wood-input" id="address" name="address" required>
                                </div>
                                <div>
                                    <label for="city" class="wood-label">City</label>
                                    <input type="text" class="wood-input" id="city" name="city" required>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label for="state" class="wood-label">State</label>
                                        <select class="wood-input wood-select" id="state" name="state" required>
                                            <option value="">Choose...</option>
                                            <option>1 - Adrar</option>
                                            <option>2 - Chlef</option>
                                            <option>3 - Laghouat</option>
                                            <option>4 - Oum El Bouaghi</option>
                                            <option>5 - Batna</option>
                                            <option>6 - Béjaïa</option>
                                            <option>7 - Biskra</option>
                                            <option>8 - Bechar</option>
                                            <option>9 - Blida</option>
                                            <option>10 - Bouira</option>
                                            <option>11 - Tamanrasset</option>
                                            <option>12 - Tébessa</option>
                                            <option>13 - Tlemcen</option>
                                            <option>14 - Tiaret</option>
                                            <option>15 - Tizi Ouzou</option>
                                            <option>16 - Alger</option>
                                            <option>17 - Djelfa</option>
                                            <option>18 - Jijel</option>
                                            <option>19 - Sétif</option>
                                            <option>20 - Saïda</option>
                                            <option>21 - Skikda</option>
                                            <option>22 - Sidi Bel Abbès</option>
                                            <option>23 - Annaba</option>
                                            <option>24 - Guelma</option>
                                            <option>25 - Constantine</option>
                                            <option>26 - Médéa</option>
                                            <option>27 - Mostaganem</option>
                                            <option>28 - M'sila</option>
                                            <option>29 - Mascara</option>
                                            <option>30 - Ouargla</option>
                                            <option>31 - Oran</option>
                                            <option>32 - El Bayadh</option>
                                            <option>33 - Illizi</option>
                                            <option>34 - Bordj Bou Arreridj</option>
                                            <option>35 - Boumerdès</option>
                                            <option>36 - El Taref</option>
                                            <option>37 - Tindouf</option>
                                            <option>38 - Tissemsilt</option>
                                            <option>39 - El Oued</option>
                                            <option>40 - Khenchela</option>
                                            <option>41 - Souk Ahras</option>
                                            <option>42 - Tipaza</option>
                                            <option>43 - Mila</option>
                                            <option>44 - Aïn Defla</option>
                                            <option>45 - Naâma</option>
                                            <option>46 - Aïn Témouchent</option>
                                            <option>47 - Ghardaïa</option>
                                            <option>48 - Relizane</option>
                                            <option>49 - Timimoun</option>
                                            <option>50 - Bordj Badji Mokhtar</option>
                                            <option>51 - Ouled Djellal</option>
                                            <option>52 - Béni Abbès</option>
                                            <option>53 - In Salah</option>
                                            <option>54 - In Guezzam</option>
                                            <option>55 - Touggourt</option>
                                            <option>56 - Djanet</option>
                                            <option>57 - El M'Ghair</option>
                                            <option>58 - El Menia</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="zip" class="wood-label">Zip</label>
                                        <input type="text" class="wood-input" id="zip" name="zip" required>
                                    </div>
                                </div>
                            </div>
                            <div class="wooden-divider my-6"></div>
                            <h4 class="wood-card-title text-lg mb-4 pb-2 border-b border-amber-200">
                                <i class="fas fa-credit-card mr-2"></i> Payment Information
                            </h4>
                            <div class="grid grid-cols-1 gap-4 mb-6">
                                <div>
                                    <label for="cc-name" class="wood-label">Name on Card</label>
                                    <input type="text" class="wood-input" id="cc-name" name="cc_name" required>
                                    <p class="text-xs text-amber-800 mt-1 ml-1">Full name as displayed on card</p>
                                </div>
                                <div>
                                    <label for="cc-number" class="wood-label">Credit Card Number</label>
                                    <input type="text" class="wood-input" id="cc-number" name="cc_number" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="cc-expiration" class="wood-label">Expiration</label>
                                        <input type="text" class="wood-input" id="cc-expiration" name="cc_expiration" placeholder="MM/YY" required>
                                    </div>
                                    <div>
                                        <label for="cc-cvv" class="wood-label">CVV</label>
                                        <input type="text" class="wood-input" id="cc-cvv" name="cc_cvv" required>
                                    </div>
                                </div>
                            </div>
                            <div class="wooden-divider my-6"></div>
                            <div class="text-center">
                                <button type="submit" class="wooden-cart-button bg-green-700 w-full py-4">
                                    <i class="fas fa-lock mr-2 text-black"></i>
                                    <span class="button-text text-black font-bold">Complete Purchase</span>
                                </button>
                                <p class="text-sm text-amber-800 mt-3">
                                    <i class="fas fa-shield-alt"></i> Your payment is secure and encrypted
                                </p>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Right Column - Order Summary -->
        <div class="w-full lg:w-1/3">
            <div class="wood-card sticky top-4 overflow-hidden fade-in">
                <div class="bg-amber-800 py-4 px-6 border-b border-amber-700">
                    <h3 class="text-xl font-bold text-white">
                        <i class="fas fa-shopping-basket mr-2"></i> Order Summary
                    </h3>
                </div>
                <div class="p-6 card-content">
                    <div class="space-y-4 mb-6">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="flex justify-between items-center pb-3 border-b border-amber-100">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-amber-50 rounded overflow-hidden mr-3 border border-amber-200 flex-shrink-0">
                                        <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>"
                                             class="w-full h-full object-contain"
                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div>
                                        <p class="font-medium text-amber-900"><?= htmlspecialchars($item['name']) ?></p>
                                        <p class="text-xs text-amber-700">Quantity: <?= $item['quantity'] ?></p>
                                    </div>
                                </div>
                                <div class="text-right text-amber-900 font-bold">
                                    $<?= number_format($item['subtotal'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-amber-100 pb-2">
                            <span class="text-amber-800">Subtotal:</span>
                            <span class="font-medium">$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="flex justify-between border-b border-amber-100 pb-2">
                            <span class="text-amber-800">Shipping:</span>
                            <span class="font-medium">$<?= number_format($shipping, 2) ?></span>
                        </div>
                        <div class="flex justify-between border-b border-amber-100 pb-2">
                            <span class="text-amber-800">Tax (<?= ($tax_rate * 100) ?>%):</span>
                            <span class="font-medium">$<?= number_format($tax, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-lg pt-2 font-bold">
                            <span class="text-amber-900">Total:</span>
                            <span class="text-amber-900">$<?= number_format($grand_total, 2) ?></span>
                        </div>
                    </div>
                    <div class="wooden-divider my-6"></div>
                    <!-- Shipping Methods -->
                    <h4 class="wood-card-title text-sm mb-3">Shipping Method</h4>
                    <div class="space-y-2 mb-6">
                        <label class="wood-checkbox block p-3 border border-amber-200 rounded-md bg-amber-50 cursor-pointer">
                            <input type="radio" name="shipping_method" form="checkout-form" value="standard" checked class="mr-2">
                            <span class="checkmark"></span>
                            <span class="font-medium text-amber-900">Standard Shipping</span>
                            <p class="text-xs text-amber-700 mt-1">Delivery in 3-5 business days</p>
                        </label>
                        <label class="wood-checkbox block p-3 border border-amber-200 rounded-md bg-amber-50 cursor-pointer">
                            <input type="radio" name="shipping_method" form="checkout-form" value="express" class="mr-2">
                            <span class="checkmark"></span>
                            <span class="font-medium text-amber-900">Express Shipping</span>
                            <p class="text-xs text-amber-700 mt-1">Delivery in 1-2 business days (+$15.00)</p>
                        </label>
                    </div>
                    <!-- Promo Code -->
                    <h4 class="wood-card-title text-sm mb-3">Promo Code</h4>
                    <div class="flex">
                        <input type="text" class="wood-input rounded-r-none flex-grow" name="promo_code" form="checkout-form" placeholder="Enter code">
                        <button type="button" class="wooden-cart-button rounded-l-none px-4">
                            <span class="button-text">Apply</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    $('.wood-input').each(function() {
        $(this).css({
            'background-color': '#f5e0c0',
            'border': '1px solid #b08463',
            'color': '#5c4033',
            'border-radius': '8px',
            'padding': '10px 12px',
            'width': '100%',
            'position': 'relative',
            'z-index': '10'
        });
    }).focus(function() {
        $(this).css({
            'border-color': '#714329',
            'box-shadow': '0 0 0 3px rgba(176, 132, 99, 0.2)',
            'outline': 'none'
        });
    }).blur(function() {
        $(this).css({
            'border-color': '#b08463',
            'box-shadow': 'none'
        });
    });
    $('#cc-number').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        $(this).val(formattedValue);
    });
    // Expiration date formatting
    $('
        let value = $(this).val().replace(/\D/g, '');
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = value.substring(0, 2);
            if (value.length > 2) {
                formattedValue += '/' + value.substring(2, 4);
            }
        }
        $(this).val(formattedValue);
    });
    // CVV input limits
    $('
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value.substring(0, 3));
    });
    // ZIP code input limits
    $('
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value.substring(0, 5));
    });
    // Radio button custom styling
    $('input[type="radio"]').each(function() {
        $(this).css({
            'position': 'relative',
            'z-index': '10'
        });
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>