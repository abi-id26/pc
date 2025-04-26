<?php
session_start();
if (isset($_SESSION['pc_build'])) {
    $component = isset($_GET['component']) ? $_GET['component'] : null;
    if ($component) {
        $components = ['cpu', 'cpu_cooler', 'motherboard', 'ram', 'storage', 'gpu', 'psu', 'case'];
        $resetIndex = array_search($component, $components);
        if ($resetIndex !== false) {
            for ($i = $resetIndex; $i < count($components); $i++) {
                $_SESSION['pc_build'][$components[$i]] = null;
            }
        }
    }
}
header('Location: build_pc.php');
exit;
?>