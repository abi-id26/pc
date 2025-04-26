<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$action = $_POST['action'] ?? '';
$response = ['success' => false];
switch ($action) {
    case 'remove':
        if (isset($_POST['id'])) {
            unset($_SESSION['cart'][$_POST['id']]);
            $response = ['success' => true];
        }
        break;
    case 'update':
        if (isset($_POST['quantities'])) {
            $quantities = json_decode($_POST['quantities'], true);
            if ($quantities && is_array($quantities)) {
                foreach ($quantities as $id => $quantity) {
                    if (isset($_SESSION['cart'][$id])) {
                        $_SESSION['cart'][$id]['quantity'] = max(1, (int)$quantity);
                    }
                }
                $response = ['success' => true];
            }
        }
        break;
}
echo json_encode($response);
?>