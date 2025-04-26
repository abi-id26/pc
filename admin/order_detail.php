<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
requireAdmin();
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}
$order_id = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) {
    header("Location: orders.php");
    exit();
}
$stmt = $pdo->prepare("
    SELECT
        p.id, p.name, p.image,
        oi.quantity, oi.price,
        (oi.quantity * oi.price) AS subtotal
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
$stmt = $pdo->prepare("
    SELECT * FROM shipping_addresses
    WHERE order_id = ?
    ORDER BY id DESC LIMIT 1
");
$stmt->execute([$order_id]);
$shipping_address = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order ?> - PC Hardware Store Admin</title>
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
                <div class="wood-breadcrumb-item">
                    <a href="orders.php">Orders</a>
                </div>
                <div class="wood-breadcrumb-item active">
                    Order ?>
                </div>
            </div>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Summary -->
                <div class="lg:col-span-2">
                    <div class="wood-card overflow-hidden rounded-lg shadow-md">
                        <div class="wooden-texture-overlay"></div>
                        <div class="p-4 bg-amber-800 text-white flex justify-between items-center">
                            <h4 class="text-xl font-bold mb-0 text-black">Order ?></h4>
                            <div class="flex space-x-2">
                                <a href="orders.php" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-amber-700 text-black">
                                    <i class="fas fa-arrow-left mr-1"></i> Back
                                </a>
                                <a href="print_order.php?id=<?= $order['id'] ?>" class="wooden-cart-button inline-flex items-center px-3 py-1 bg-blue-600 text-white" target="_blank">
                                    <i class="fas fa-print mr-1"></i> Print
                                </a>
                                <?php if ($order['status'] == 'processing' || $order['status'] == 'pending'): ?>
                                    <a href="update_order.php?id=<?= $order['id'] ?>&status=completed"
                                       class="wooden-cart-button inline-flex items-center px-3 py-1 bg-green-600 text-white"
                                       onclick="return confirm('Mark this order as completed?')">
                                        <i class="fas fa-check mr-1"></i> Complete
                                    </a>
                                    <button type="button" id="topCancelBtn"
                                       class="wooden-cart-button inline-flex items-center px-3 py-1 bg-red-600 text-white cancel-order-btn">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                    <h5 class="text-amber-900 font-bold mb-3">Order Information</h5>
                                    <p class="mb-2 text-amber-800">
                                        <span class="font-semibold">Date:</span><br>
                                        <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?>
                                    </p>
                                    <p class="mb-2 text-amber-800">
                                        <span class="font-semibold">Status:</span><br>
                                        <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?=
                                            ($order['status'] == 'completed') ? 'bg-green-600' :
                                            (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                        ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </p>
                                    <p class="text-amber-800">
                                        <span class="font-semibold">Total Amount:</span><br>
                                        <span class="text-xl font-bold">$<?= number_format($order['total'], 2) ?></span>
                                    </p>
                                </div>
                                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                    <h5 class="text-amber-900 font-bold mb-3">Customer Information</h5>
                                    <p class="mb-2 text-amber-800">
                                        <span class="font-semibold">Username:</span><br>
                                        <?= htmlspecialchars($order['username']) ?>
                                    </p>
                                    <p class="mb-2 text-amber-800">
                                        <span class="font-semibold">Email:</span><br>
                                        <?= htmlspecialchars($order['email']) ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($shipping_address): ?>
                            <div class="bg-amber-50 p-4 rounded-lg border border-amber-200 mb-6">
                                <h5 class="text-amber-900 font-bold mb-3">Shipping Information</h5>
                                <p class="mb-2 text-amber-800">
                                    <span class="font-semibold">Name:</span><br>
                                    <?= htmlspecialchars($shipping_address['first_name'] . ' ' . $shipping_address['last_name']) ?>
                                </p>
                                <p class="mb-2 text-amber-800">
                                    <span class="font-semibold">Address:</span><br>
                                    <?= htmlspecialchars($shipping_address['address']) ?><br>
                                    <?= htmlspecialchars($shipping_address['city']) . ', ' . htmlspecialchars($shipping_address['state']) . ' ' . htmlspecialchars($shipping_address['zip']) ?>
                                </p>
                            </div>
                            <?php endif; ?>
                            <!-- Order Items -->
                            <div class="overflow-x-auto">
                                <table class="wood-table w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Product</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <img src="../assets/images/products/<?= htmlspecialchars($item['image']) ?>"
                                                             class="w-12 h-12 object-contain mr-3 rounded border border-amber-200"
                                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                                        <div>
                                                            <div class="font-medium text-amber-900"><?= htmlspecialchars($item['name']) ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right text-amber-800">$<?= number_format($item['price'], 2) ?></td>
                                                <td class="text-center text-amber-800"><?= $item['quantity'] ?></td>
                                                <td class="text-right font-bold text-amber-900">$<?= number_format($item['subtotal'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right font-bold text-amber-900">Subtotal:</td>
                                            <td class="text-right text-amber-800">$<?= number_format($order['total'] * 0.9, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right font-bold text-amber-900">Tax (10%):</td>
                                            <td class="text-right text-amber-800">$<?= number_format($order['total'] * 0.1, 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right font-bold text-amber-900">Total:</td>
                                            <td class="text-right font-bold text-amber-900">$<?= number_format($order['total'], 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Actions & Notes -->
                <div class="lg:col-span-1">
                    <div class="wood-card overflow-hidden rounded-lg shadow-md mb-6">
                        <div class="wooden-texture-overlay"></div>
                        <div class="p-4 bg-amber-800 text-black">
                            <h4 class="text-lg font-bold mb-0">Order Actions</h4>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                    <h5 class="text-amber-900 font-bold mb-2">Order Status</h5>
                                    <p class="text-amber-800 mb-2">Current Status:
                                        <span class="wood-badge inline-block px-2 py-1 rounded text-white text-xs <?=
                                            ($order['status'] == 'completed') ? 'bg-green-600' :
                                            (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                        ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </p>
                                    <?php if ($order['status'] == 'processing' || $order['status'] == 'pending'): ?>
                                        <div class="space-y-2">
                                            <a href="update_order.php?id=<?= $order['id'] ?>&status=completed"
                                               class="wooden-cart-button w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white">
                                                <i class="fas fa-check mr-2"></i> Mark as Completed
                                            </a>
                                            <button type="button" id="cancelOrderBtn"
                                               class="wooden-cart-button w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white">
                                                <i class="fas fa-times mr-2"></i> Cancel Order
                                            </button>
                                            <!-- Cancel Order Form (hidden by default) -->
                                            <div id="cancelOrderForm" class="mt-4 hidden relative z-20 bg-white p-4 rounded-lg border border-amber-300">
                                                <form action="update_order.php" method="get">
                                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <div class="mb-3">
                                                        <label for="cancel_reason" class="block text-amber-900 font-medium mb-1">Cancellation Reason:</label>
                                                        <textarea id="cancel_reason" name="reason" rows="3"
                                                            class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                            placeholder="Enter reason for cancellation..."></textarea>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button type="submit"
                                                            class="wooden-cart-button flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white">
                                                            <i class="fas fa-times mr-2"></i> Confirm Cancellation
                                                        </button>
                                                        <button type="button" id="cancelFormClose"
                                                            class="wooden-cart-button flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-500 text-white">
                                                            <i class="fas fa-times mr-2"></i> Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                    <h5 class="text-amber-900 font-bold mb-2">Quick Actions</h5>
                                    <div class="space-y-2">
                                        <a href="print_order.php?id=<?= $order['id'] ?>"
                                           class="wooden-cart-button w-full inline-flex items-center justify-center px-3 py-2 bg-amber-700 text-black">
                                            <i class="fas fa-print mr-2"></i> Print Order
                                        </a>
                                        <a href="mailto:<?= htmlspecialchars($order['email']) ?>"
                                           class="wooden-cart-button w-full inline-flex items-center justify-center px-3 py-2 bg-amber-700 text-black">
                                            <i class="fas fa-envelope mr-2"></i> Email Customer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($order['status'] == 'cancelled'):
                        $tableExists = false;
                        try {
                            $checkTable = $pdo->query("SHOW TABLES LIKE 'cancellations'");
                            $tableExists = $checkTable->rowCount() > 0;
                        } catch (PDOException $e) {
                            $tableExists = false;
                        }
                        if ($tableExists):
                            $stmt = $pdo->prepare("
                                SELECT reason, cancelled_at
                                FROM cancellations
                                WHERE order_id = ?
                                ORDER BY cancelled_at DESC
                                LIMIT 1
                            ");
                            $stmt->execute([$order_id]);
                            $cancellation = $stmt->fetch();
                            if ($cancellation):
                    ?>
                    <div class="wood-card overflow-hidden rounded-lg shadow-md mt-6">
                        <div class="wooden-texture-overlay"></div>
                        <div class="p-4 bg-red-600 text-white">
                            <h4 class="text-lg font-bold mb-0 flex items-center">
                                <i class="fas fa-ban mr-2"></i> Cancellation Details
                            </h4>
                        </div>
                        <div class="p-4">
                            <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                                <p class="mb-2 text-amber-800">
                                    <span class="font-semibold">Cancelled on:</span><br>
                                    <?= date('F j, Y \a\t g:i A', strtotime($cancellation['cancelled_at'])) ?>
                                </p>
                                <p class="text-amber-800">
                                    <span class="font-semibold">Reason:</span><br>
                                    <?= nl2br(htmlspecialchars($cancellation['reason'] ?: 'No reason provided')) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                            endif;
                        endif;
                    endif;
                    ?>
                    <!-- Order Notes -->
                    <div class="wood-card overflow-hidden rounded-lg shadow-md">
                        <div class="wooden-texture-overlay"></div>
                        <div class="p-4 bg-amber-800 text-black">
                            <h4 class="text-lg font-bold mb-0">Order Notes</h4>
                        </div>
                        <div class="p-4">
                            <?php
                            $tableExists = false;
                            try {
                                $checkTable = $pdo->query("SHOW TABLES LIKE 'order_notes'");
                                $tableExists = $checkTable->rowCount() > 0;
                            } catch (PDOException $e) {
                                $tableExists = false;
                            }
                            if ($tableExists) {
                            ?>
                            <form action="add_order_note.php" method="post" class="mb-4">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <div class="mb-3">
                                    <textarea name="note" rows="3"
                                              class="wood-input w-full"
                                              placeholder="Add a note about this order..."></textarea>
                                </div>
                                <button type="submit"
                                        class="wooden-cart-button w-full inline-flex items-center justify-center px-3 py-2 bg-amber-700 text-white">
                                    <i class="fas fa-plus mr-2"></i> Add Note
                                </button>
                            </form>
                            <div class="space-y-3">
                                <?php
                                try {
                                    $stmt = $pdo->prepare("
                                        SELECT ord_notes.*, a.username as admin_name
                                        FROM order_notes ord_notes
                                        LEFT JOIN admins a ON ord_notes.admin_id = a.id
                                        WHERE ord_notes.order_id = ?
                                        ORDER BY ord_notes.created_at DESC
                                    ");
                                    $stmt->execute([$order_id]);
                                    $notes = $stmt->fetchAll();
                                    foreach ($notes as $note):
                                    ?>
                                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-sm text-amber-800">
                                                    <i class="fas fa-user-shield mr-1"></i>
                                                    <?= htmlspecialchars($note['admin_name']) ?>
                                                </span>
                                                <span class="text-xs text-amber-600">
                                                    <?= date('M j, Y g:i A', strtotime($note['created_at'])) ?>
                                                </span>
                                            </div>
                                            <p class="text-amber-900"><?= nl2br(htmlspecialchars($note['note'])) ?></p>
                                        </div>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<div class="bg-amber-50 p-3 rounded-lg border border-amber-200">';
                                    echo '<p class="text-amber-900">No notes available for this order.</p>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <?php } else { ?>
                                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                                    <p class="text-amber-900">Order notes feature is not available. The required database tables do not exist.</p>
                                </div>
                            <?php } ?>
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
        $('#cancelOrderBtn, .cancel-order-btn, #topCancelBtn').click(function(e) {
            e.preventDefault();
            $('#cancelOrderForm').removeClass('hidden').fadeIn(300);
            setTimeout(function() {
                $('#cancel_reason').focus();
            }, 300);
            $('html, body').animate({
                scrollTop: $('#cancelOrderForm').offset().top - 100
            }, 500);
        });
        $('#cancelFormClose').click(function(e) {
            e.preventDefault();
            $('#cancelOrderForm').fadeOut(300, function() {
                $(this).addClass('hidden');
            });
        });
    });
    </script>
</body>
</html>