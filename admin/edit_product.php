<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit();
}

$error = null;
$maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $socket_type = isset($_POST['socket_type']) ? $_POST['socket_type'] : null;
    $ram_type = isset($_POST['ram_type']) ? $_POST['ram_type'] : null;
    $wattage = isset($_POST['wattage']) ? $_POST['wattage'] : null;
    $form_factor = isset($_POST['form_factor']) ? $_POST['form_factor'] : null;
    
    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['size'] > $maxFileSize) {
            $error = 'Error: File size exceeds the maximum limit of 2MB.';
        } else {
            $target_dir = "../assets/images/products/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Delete old image if not default
                if ($image !== 'default.png' && file_exists($target_dir . $image)) {
                unlink($target_dir . $image);
            }
            
            // Generate unique filename
                $image = $category . '_' . uniqid() . '.' . $imageFileType;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
            } else {
                $error = 'Error: Uploaded file is not a valid image.';
            }
        }
    }
    
    if (!$error) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, image = ?, socket_type = ?, ram_type = ?, wattage = ?, form_factor = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $stock, $category, $image, $socket_type, $ram_type, $wattage, $form_factor, $id]);
    
    header("Location: products.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - PC Hardware Store</title>
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
                    <a href="products.php">Products</a>
                </div>
                <div class="wood-breadcrumb-item active">
                    Edit Product
                </div>
            </div>
            
            <div class="wood-card overflow-hidden rounded-lg shadow-md mb-8">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h3 class="text-xl font-bold mb-0 flex items-center text-black">
                        <i class="fas fa-edit mr-2 text-black"></i> Edit Product: <?= htmlspecialchars($product['name']) ?>
                    </h3>
                </div>
                <div class="p-6 relative z-10">
                    <?php if ($error): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                            <p><?= $error ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-amber-900 font-semibold mb-2">Product Name</label>
                                <input type="text" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>
                            
                            <div>
                                <label for="price" class="block text-amber-900 font-semibold mb-2">Price ($)</label>
                                <input type="number" step="0.01" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                            </div>
                            
                            <div>
                                <label for="stock" class="block text-amber-900 font-semibold mb-2">Stock Quantity</label>
                                <input type="number" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
                            </div>
                            
                            <div>
                                <label for="category" class="block text-amber-900 font-semibold mb-2">Category</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="category" name="category" required onchange="showSpecificFields()">
                        <option value="CPU" <?= $product['category'] == 'CPU' ? 'selected' : '' ?>>CPU</option>
                        <option value="GPU" <?= $product['category'] == 'GPU' ? 'selected' : '' ?>>GPU</option>
                        <option value="RAM" <?= $product['category'] == 'RAM' ? 'selected' : '' ?>>RAM</option>
                        <option value="Storage" <?= $product['category'] == 'Storage' ? 'selected' : '' ?>>Storage</option>
                        <option value="Motherboard" <?= $product['category'] == 'Motherboard' ? 'selected' : '' ?>>Motherboard</option>
                                    <option value="Power Supply" <?= $product['category'] == 'Power Supply' ? 'selected' : '' ?>>Power Supply</option>
                        <option value="Case" <?= $product['category'] == 'Case' ? 'selected' : '' ?>>Case</option>
                                    <option value="CPU Cooler" <?= $product['category'] == 'CPU Cooler' ? 'selected' : '' ?>>CPU Cooler</option>
                                    <option value="Monitor" <?= $product['category'] == 'Monitor' ? 'selected' : '' ?>>Monitor</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-amber-900 font-semibold mb-2">Description</label>
                            <textarea class="form-textarea w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                id="description" name="description" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>
                        
                        <!-- Category-specific fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Socket type field (for CPU/Motherboard) -->
                            <div id="socket_type_div" class="<?= in_array($product['category'], ['CPU', 'Motherboard']) ? '' : 'hidden' ?>">
                                <label for="socket_type" class="block text-amber-900 font-semibold mb-2">Socket Type</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="socket_type" name="socket_type">
                                    <option value="">N/A</option>
                                    <option value="LGA1700" <?= $product['socket_type'] == 'LGA1700' ? 'selected' : '' ?>>LGA1700</option>
                                    <option value="LGA1200" <?= $product['socket_type'] == 'LGA1200' ? 'selected' : '' ?>>LGA1200</option>
                                    <option value="AM4" <?= $product['socket_type'] == 'AM4' ? 'selected' : '' ?>>AM4</option>
                                    <option value="AM5" <?= $product['socket_type'] == 'AM5' ? 'selected' : '' ?>>AM5</option>
                                </select>
                            </div>
                            
                            <!-- RAM type field (for RAM/Motherboard) -->
                            <div id="ram_type_div" class="<?= in_array($product['category'], ['RAM', 'Motherboard']) ? '' : 'hidden' ?>">
                                <label for="ram_type" class="block text-amber-900 font-semibold mb-2">RAM Type</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="ram_type" name="ram_type">
                                    <option value="">N/A</option>
                                    <option value="DDR4" <?= $product['ram_type'] == 'DDR4' ? 'selected' : '' ?>>DDR4</option>
                                    <option value="DDR5" <?= $product['ram_type'] == 'DDR5' ? 'selected' : '' ?>>DDR5</option>
                                </select>
                            </div>
                            
                            <!-- Wattage field (for Power Supply/GPU) -->
                            <div id="wattage_div" class="<?= in_array($product['category'], ['GPU', 'Power Supply']) ? '' : 'hidden' ?>">
                                <label for="wattage" class="block text-amber-900 font-semibold mb-2">Wattage</label>
                                <input type="number" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="wattage" name="wattage" value="<?= htmlspecialchars($product['wattage']) ?>">
                            </div>
                            
                            <!-- Form factor field (for Case/Motherboard) -->
                            <div id="form_factor_div" class="<?= in_array($product['category'], ['Case', 'Motherboard']) ? '' : 'hidden' ?>">
                                <label for="form_factor" class="block text-amber-900 font-semibold mb-2">Form Factor</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="form_factor" name="form_factor">
                                    <option value="">N/A</option>
                                    <option value="ATX" <?= $product['form_factor'] == 'ATX' ? 'selected' : '' ?>>ATX</option>
                                    <option value="Micro-ATX" <?= $product['form_factor'] == 'Micro-ATX' ? 'selected' : '' ?>>Micro-ATX</option>
                                    <option value="Mini-ITX" <?= $product['form_factor'] == 'Mini-ITX' ? 'selected' : '' ?>>Mini-ITX</option>
                    </select>
                </div>
                            
                            <!-- Refresh rate field (for Monitor) -->
                            <div id="refresh_rate_div" class="<?= $product['category'] == 'Monitor' ? '' : 'hidden' ?>">
                                <label for="refresh_rate" class="block text-amber-900 font-semibold mb-2">Refresh Rate (Hz)</label>
                                <input type="number" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                    id="refresh_rate" name="refresh_rate" value="<?= htmlspecialchars($product['refresh_rate'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="image" class="block text-amber-900 font-semibold mb-2">Product Image</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-amber-300 border-dashed rounded-lg cursor-pointer bg-amber-50 hover:bg-amber-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-amber-500 text-3xl mb-2"></i>
                                            <p class="mb-2 text-sm text-amber-700"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-amber-600">PNG, JPG or WEBP (MAX. 2MB)</p>
                                        </div>
                                        <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>Current image: <?= htmlspecialchars($product['image']) ?></p>
                                    <p class="text-xs">Leave empty to keep the current image</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col justify-between">
                                <div>
                                    <h4 class="text-amber-900 font-semibold mb-2">Current Image:</h4>
                                    <div class="flex items-center justify-center p-2 bg-white rounded-lg border border-amber-300 h-40">
                    <?php if ($product['image'] !== 'default.png'): ?>
                                            <img src="../assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="Current Image" class="max-h-32 object-contain">
                                        <?php else: ?>
                                            <div class="text-amber-700 flex flex-col items-center justify-center">
                                                <i class="fas fa-image text-3xl mb-2"></i>
                                                <p>Default Image</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="imagePreview" class="hidden">
                                    <h4 class="text-amber-900 font-semibold mb-2">New Image Preview:</h4>
                                    <div class="flex items-center justify-center p-2 bg-white rounded-lg border border-amber-300">
                                        <img id="preview" src="#" alt="New image preview" class="max-h-32 object-contain" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="products.php" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-amber-800 text-black">
                                <i class="fas fa-arrow-left mr-2 text-black"></i> Cancel
                            </a>
                            <button type="submit" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-green-600 text-black">
                                <i class="fas fa-save mr-2 text-black"></i> Update Product
                            </button>
                        </div>
                    </form>
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
        // Add wood texture to cards
        $('.wood-card').each(function() {
            if (!$(this).find('.wooden-texture-footer').length) {
                $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
            }
        });
        
        // Image preview
        $('#image').change(function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').removeClass('hidden');
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        
        // Animate the dashboard cards
        $('.wood-card').addClass('fade-in');
        
        // Add hover effects to buttons
        $('.wooden-cart-button').hover(
            function() {
                $(this).find('i').animate({ marginRight: '12px' }, 200);
            },
            function() {
                $(this).find('i').animate({ marginRight: '0.5rem' }, 200);
            }
        );
    });
    
    // Show/hide category-specific fields
    function showSpecificFields() {
        // Get the selected category
        var category = document.getElementById('category').value;
        
        // Hide all specific fields first
        document.getElementById('socket_type_div').classList.add('hidden');
        document.getElementById('ram_type_div').classList.add('hidden');
        document.getElementById('wattage_div').classList.add('hidden');
        document.getElementById('form_factor_div').classList.add('hidden');
        document.getElementById('refresh_rate_div').classList.add('hidden');
        
        // Show fields based on category
        if (category === 'CPU') {
            document.getElementById('socket_type_div').classList.remove('hidden');
        } else if (category === 'Motherboard') {
            document.getElementById('socket_type_div').classList.remove('hidden');
            document.getElementById('ram_type_div').classList.remove('hidden');
            document.getElementById('form_factor_div').classList.remove('hidden');
        } else if (category === 'RAM') {
            document.getElementById('ram_type_div').classList.remove('hidden');
        } else if (category === 'GPU') {
            document.getElementById('wattage_div').classList.remove('hidden');
        } else if (category === 'Power Supply') {
            document.getElementById('wattage_div').classList.remove('hidden');
        } else if (category === 'Case') {
            document.getElementById('form_factor_div').classList.remove('hidden');
        } else if (category === 'Monitor') {
            document.getElementById('refresh_rate_div').classList.remove('hidden');
        }
    }
    </script>
</body>
</html>