<?php
session_start();
require_once 'includes/db.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate order ID
if (!isset($_POST['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'];
$reason = $_POST['reason'] ?? '';

// Ensure the cancellations table exists
try {
    // Check if table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'cancellations'");
    if ($tableCheck->rowCount() == 0) {
        // Create the cancellations table
        $pdo->exec("CREATE TABLE IF NOT EXISTS cancellations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            reason TEXT,
            cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id)
        )");
    }
} catch (PDOException $e) {
    // Log the error, but continue - not critical
    error_log("Error checking/creating cancellations table: " . $e->getMessage());
}

// First verify the order belongs to user and is in processing status
$stmt = $pdo->prepare("
    SELECT id, status 
    FROM orders 
    WHERE id = ? 
    AND user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    $_SESSION['error'] = "Order not found or does not belong to you.";
    header("Location: order_history.php");
    exit();
}

if ($order['status'] !== 'pending') {
    $_SESSION['error'] = "Only orders with 'pending' status can be cancelled.";
    header("Location: order_details.php?id=$order_id");
    exit();
}

// Update order status
try {
    $pdo->beginTransaction();
    
    // Update order status
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'cancelled' 
        WHERE id = ? 
        AND user_id = ? 
        AND status = 'pending'
    ");
    $stmt->execute([$order_id, $user_id]);
    
    // Log cancellation reason
    try {
        $stmt = $pdo->prepare("
            INSERT INTO cancellations (order_id, reason) 
            VALUES (?, ?)
        ");
        $stmt->execute([$order_id, $reason]);
    } catch (PDOException $e) {
        // Log error but don't stop the cancellation process
        error_log("Could not save cancellation reason: " . $e->getMessage());
    }
    
    $pdo->commit();
    $_SESSION['success'] = "Order #$order_id has been cancelled successfully.";
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Error cancelling order: " . $e->getMessage());
    $_SESSION['error'] = "There was a problem cancelling your order. Please try again or contact support.";
}

header("Location: order_details.php?id=$order_id");
exit();
?>