<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Security checks
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order summary
$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: order_history.php");
    exit();
}

// Fetch order items
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

// Fetch cancellation details if order is cancelled
$cancellation = null;
if ($order['status'] === 'cancelled') {
    // Check if cancellations table exists
    $tableExists = false;
    try { 
        $checkTable = $pdo->query("SHOW TABLES LIKE 'cancellations'");
        $tableExists = $checkTable->rowCount() > 0;
    } catch (PDOException $e) {
        // Table doesn't exist
        $tableExists = false;
    }
    
    if ($tableExists) {
        $stmt = $pdo->prepare("
            SELECT reason, cancelled_at 
            FROM cancellations 
            WHERE order_id = ? 
            ORDER BY cancelled_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$order_id]);
        $cancellation = $stmt->fetch();
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item">
            <a href="my_account.php">My Account</a>
        </div>
        <div class="wood-breadcrumb-item">
            <a href="order_history.php">Order History</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Order #<?= $order['id'] ?>
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
                    <div class="p-4 bg-amber-800 text-white flex justify-between items-center">
                        <h4 class="text-xl font-bold mb-0">Order #<?= $order['id'] ?></h4>
                        <a href="order_history.php" class="px-3 py-1 bg-amber-700 hover:bg-amber-600 text-white rounded transition-colors text-sm flex items-center">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Order History
                        </a>
                    </div>
                    <div class="p-5">
                        <!-- Order Summary -->
                        <div class="mb-6">
                            <div class="flex flex-col md:flex-row justify-between bg-amber-50 p-4 rounded-lg border border-amber-200">
                                <div class="mb-4 md:mb-0">
                                    <h5 class="text-amber-900 font-bold mb-2">Order Details</h5>
                                    <p class="mb-1 text-amber-800"><strong>Date:</strong> <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
                                    <p class="mb-1 text-amber-800"><strong>Status:</strong> 
                                        <span class="inline-block px-2 py-1 rounded text-white text-xs <?= 
                                            ($order['status'] == 'completed') ? 'bg-green-600' : 
                                            (($order['status'] == 'cancelled') ? 'bg-red-600' : 'bg-amber-600')
                                        ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <h5 class="text-amber-900 font-bold mb-2">Payment Summary</h5>
                                    <p class="mb-1 text-amber-800"><strong>Subtotal:</strong> $<?= number_format($order['total'] * 0.9, 2) ?></p>
                                    <p class="mb-1 text-amber-800"><strong>Tax (10%):</strong> $<?= number_format($order['total'] * 0.1, 2) ?></p>
                                    <p class="text-lg mt-2 font-bold text-amber-900"><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
        </div>
    </div>

    <?php if ($order['status'] === 'pending'): ?>
        <div class="mt-4">
            <button id="showCancelModal" class="wooden-cart-button bg-red-600 hover:bg-red-700 mt-3 flex items-center justify-center w-full md:w-auto px-4 py-2">
                <i class="fas fa-times-circle mr-1"></i> Cancel Order
            </button>
        </div>
        
        <!-- Cancel Order Modal -->
        <div id="cancelOrderModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-4 bg-amber-800 text-white rounded-t-lg">
                    <h5 class="font-bold text-lg flex justify-between items-center">
                        <span>Confirm Cancellation</span>
                        <button id="closeCancelModal" class="text-white hover:text-amber-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </h5>
                </div>
                <div class="p-6">
                    <p class="text-amber-900 mb-4">Are you sure you want to cancel order #<?= $order['id'] ?>?</p>
                    <form action="cancel_order.php" method="post">
                        <div class="mb-4">
                            <label for="reason" class="block text-amber-800 mb-2">Reason (optional):</label>
                            <textarea class="w-full p-2 border border-amber-300 rounded-lg focus:border-amber-500 focus:ring focus:ring-amber-200" 
                                      id="reason" name="reason" rows="3" placeholder="Please explain why you're cancelling this order..."></textarea>
                        </div>
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="flex space-x-2">
                            <button type="submit" class="wooden-cart-button bg-red-600 hover:bg-red-700 flex-1 py-2">
                                <i class="fas fa-check mr-1"></i> Confirm Cancellation
                            </button>
                            <button type="button" id="cancelCancellation" class="wooden-cart-button bg-gray-500 hover:bg-gray-600 flex-1 py-2">
                                <i class="fas fa-times mr-1"></i> Nevermind
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($order['status'] === 'cancelled'): ?>
        <!-- Cancellation Details -->
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h5 class="flex items-center text-lg text-red-700 mb-2">
                <i class="fas fa-ban mr-2"></i> Cancellation Details
            </h5>
            
            <?php if ($cancellation): ?>
                <p class="mb-1 text-amber-900"><strong>Cancelled on:</strong> <?= date('F j, Y \a\t g:i A', strtotime($cancellation['cancelled_at'])) ?></p>
                <?php if (!empty($cancellation['reason'])): ?>
                    <p class="text-amber-900"><strong>Reason:</strong> <?= nl2br(htmlspecialchars($cancellation['reason'])) ?></p>
                <?php else: ?>
                    <p class="text-amber-700 italic">No reason provided</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="mb-1 text-amber-900"><strong>Cancelled on:</strong> <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                <p class="text-amber-700 italic">Additional cancellation details are not available</p>
            <?php endif; ?>
            
            <div class="mt-3 border-t border-red-200 pt-3">
                <p class="text-amber-800">
                    <strong>Note:</strong> Cancelled orders cannot be reinstated. If you still want these items, 
                    please place a new order. If you have any questions, please <a href="contact.php" class="text-red-600 hover:underline">contact us</a>.
                </p>
            </div>
        </div>
    <?php endif; ?>
                        </div>

    <!-- Ordered Items -->
                        <div>
                            <h5 class="flex items-center text-lg text-amber-900 mb-3">
                                <i class="fas fa-box-open mr-2"></i> Ordered Items
                            </h5>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead>
                                        <tr class="bg-amber-50 text-amber-800 border-b border-amber-200">
                                            <th class="px-4 py-2 text-left" width="80">Image</th>
                                            <th class="px-4 py-2 text-left">Product</th>
                                            <th class="px-4 py-2 text-right">Price</th>
                                            <th class="px-4 py-2 text-center">Qty</th>
                                            <th class="px-4 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                                            <tr class="border-b border-amber-100">
                                                <td class="px-4 py-3">
                            <img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                                        class="rounded-md border border-amber-200" width="60" alt="<?= htmlspecialchars($item['name']) ?>">
                        </td>
                                                <td class="px-4 py-3 text-amber-900 font-medium"><?= htmlspecialchars($item['name']) ?></td>
                                                <td class="px-4 py-3 text-right text-amber-800">$<?= number_format($item['price'], 2) ?></td>
                                                <td class="px-4 py-3 text-center text-amber-800"><?= $item['quantity'] ?></td>
                                                <td class="px-4 py-3 text-right font-bold text-amber-900">$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle cancel order modal
    document.addEventListener('DOMContentLoaded', function() {
        const showCancelModal = document.getElementById('showCancelModal');
        const cancelOrderModal = document.getElementById('cancelOrderModal');
        const closeCancelModal = document.getElementById('closeCancelModal');
        const cancelCancellation = document.getElementById('cancelCancellation');
        
        if (showCancelModal) {
            showCancelModal.addEventListener('click', function() {
                cancelOrderModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        }
        
        if (closeCancelModal) {
            closeCancelModal.addEventListener('click', function() {
                cancelOrderModal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            });
        }
        
        if (cancelCancellation) {
            cancelCancellation.addEventListener('click', function() {
                cancelOrderModal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scrolling
            });
        }
        
        // Close modal when clicking outside of it
        if (cancelOrderModal) {
            cancelOrderModal.addEventListener('click', function(e) {
                if (e.target === cancelOrderModal) {
                    cancelOrderModal.classList.add('hidden');
                    document.body.style.overflow = 'auto'; // Re-enable scrolling
                }
            });
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>