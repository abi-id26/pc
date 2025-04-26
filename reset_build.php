<?php
session_start();

// Check if the PC build session exists
if (isset($_SESSION['pc_build'])) {
    // Get the component to reset
    $component = isset($_GET['component']) ? $_GET['component'] : null;
    
    if ($component) {
        // Reset the specified component and all components that come after it
        $components = ['cpu', 'cpu_cooler', 'motherboard', 'ram', 'storage', 'gpu', 'psu', 'case'];
        $resetIndex = array_search($component, $components);
        
        if ($resetIndex !== false) {
            // Reset all components starting from the specified component
            for ($i = $resetIndex; $i < count($components); $i++) {
                $_SESSION['pc_build'][$components[$i]] = null;
            }
        }
    }
}

// Redirect back to the build page
header('Location: build_pc.php');
exit;
?> 