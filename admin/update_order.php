<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

requireAdmin();

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    $_SESSION['error'] = "Missing required parameters.";
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
$status = $_GET['status'];

// Validate status
$allowed_statuses = ['pending', 'pending', 'completed', 'cancelled'];
if (!in_array($status, $allowed_statuses)) {
    $_SESSION['error'] = "Invalid order status.";
    header("Location: orders.php");
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Update order status
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $result = $stmt->execute([$status, $order_id]);
    
    if (!$result) {
        throw new Exception("Failed to update order status.");
    }
    
    // If order is cancelled, restore product stock and log cancellation reason
    if ($status == 'cancelled') {
        // Get order items
        $stmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll();
        
        // Update stock for each product
        foreach ($items as $item) {
            $restockStmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $restockResult = $restockStmt->execute([$item['quantity'], $item['product_id']]);
            
            if (!$restockResult) {
                throw new Exception("Failed to restore product stock.");
            }
        }
        
        // Check if cancellations table exists
        $tableExists = false;
        try { 
            $checkTable = $pdo->query("SHOW TABLES LIKE 'cancellations'");
            $tableExists = $checkTable->rowCount() > 0;
        } catch (PDOException $e) {
            // Table doesn't exist
            $tableExists = false;
        }
        
        // Create cancellations table if it doesn't exist
        if (!$tableExists) {
            $createTable = $pdo->exec("
                CREATE TABLE IF NOT EXISTS cancellations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT,
                    reason TEXT DEFAULT NULL,
                    cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (order_id) REFERENCES orders(id)
                )
            ");
        }
        
        // Log the cancellation
        $reason = "Cancelled by administrator";
        if (isset($_GET['reason'])) {
            $reason = $_GET['reason'];
        }
        
        $cancelStmt = $pdo->prepare("INSERT INTO cancellations (order_id, reason) VALUES (?, ?)");
        $cancelResult = $cancelStmt->execute([$order_id, $reason]);
        
        if (!$cancelResult) {
            throw new Exception("Failed to log cancellation reason.");
        }
    }
    
    // Commit the transaction
    $pdo->commit();
    
    $_SESSION['success'] = "Order #$order_id has been " . ($status == 'completed' ? 'marked as completed' : 
        ($status == 'cancelled' ? 'cancelled' : 'updated to ' . $status));
    
} catch (Exception $e) {
    // Rollback the transaction on error
    $pdo->rollBack();
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

// Redirect back to order detail page
header("Location: order_detail.php?id=$order_id");
exit();
?>