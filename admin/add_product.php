<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
requireAdmin();
$maxFileSize = 2 * 1024 * 1024;
$error = null;
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
    $image = 'default.png';
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['size'] > $maxFileSize) {
            $error = 'Error: File size exceeds the maximum limit of 2MB.';
        } else {
            $target_dir = "../assets/images/products/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $image = $category . '_' . uniqid() . '.' . $imageFileType;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
            } else {
                $error = 'Error: Uploaded file is not a valid image.';
            }
        }
    }
    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category, image, socket_type, ram_type, wattage, form_factor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $category, $image, $socket_type, $ram_type, $wattage, $form_factor]);
        header("Location: products.php");
        exit();
    }
}
$categoryStmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - PC Hardware Store</title>
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
                    Add Product
                </div>
            </div>
            <div class="wood-card overflow-hidden rounded-lg shadow-md mb-8">
                <div class="wooden-texture-overlay"></div>
                <div class="p-4 bg-amber-800 text-white">
                    <h3 class="text-xl font-bold mb-0 flex items-center text-black">
                        <i class="fas fa-plus-circle mr-2 text-black"></i> Add New Product
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
                                    id="name" name="name" required>
                            </div>
                            <div>
                                <label for="price" class="block text-amber-900 font-semibold mb-2">Price ($)</label>
                                <input type="number" step="0.01" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="price" name="price" required>
                            </div>
                            <div>
                                <label for="stock" class="block text-amber-900 font-semibold mb-2">Stock Quantity</label>
                                <input type="number" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="stock" name="stock" required>
                            </div>
                            <div>
                                <label for="category" class="block text-amber-900 font-semibold mb-2">Category</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="category" name="category" required onchange="showSpecificFields()">
                                    <option value="" disabled selected>Select a category</option>
                        <option value="CPU">CPU</option>
                        <option value="GPU">GPU</option>
                        <option value="RAM">RAM</option>
                        <option value="Storage">Storage</option>
                        <option value="Motherboard">Motherboard</option>
                                    <option value="Power Supply">Power Supply</option>
                        <option value="Case">Case</option>
                                    <option value="CPU Cooler">CPU Cooler</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="Peripherals">Peripherals</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Accessories">Accessories</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="description" class="block text-amber-900 font-semibold mb-2">Description</label>
                            <textarea class="form-textarea w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                id="description" name="description" rows="4" required></textarea>
                        </div>
                        <!-- Category-specific fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Socket type field (for CPU/Motherboard) -->
                            <div id="socket_type_div" class="hidden">
                                <label for="socket_type" class="block text-amber-900 font-semibold mb-2">Socket Type</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="socket_type" name="socket_type">
                                    <option value="">N/A</option>
                                    <option value="LGA1700">LGA1700</option>
                                    <option value="LGA1200">LGA1200</option>
                                    <option value="AM4">AM4</option>
                                    <option value="AM5">AM5</option>
                                </select>
                            </div>
                            <!-- RAM type field (for RAM/Motherboard) -->
                            <div id="ram_type_div" class="hidden">
                                <label for="ram_type" class="block text-amber-900 font-semibold mb-2">RAM Type</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="ram_type" name="ram_type">
                                    <option value="">N/A</option>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </select>
                            </div>
                            <!-- Wattage field (for Power Supply/GPU) -->
                            <div id="wattage_div" class="hidden">
                                <label for="wattage" class="block text-amber-900 font-semibold mb-2">Wattage</label>
                                <input type="number" min="0" class="form-input w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="wattage" name="wattage">
                            </div>
                            <!-- Form factor field (for Case/Motherboard) -->
                            <div id="form_factor_div" class="hidden">
                                <label for="form_factor" class="block text-amber-900 font-semibold mb-2">Form Factor</label>
                                <select class="form-select w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    id="form_factor" name="form_factor">
                                    <option value="">N/A</option>
                                    <option value="ATX">ATX</option>
                                    <option value="Micro-ATX">Micro-ATX</option>
                                    <option value="Mini-ITX">Mini-ITX</option>
                    </select>
                </div>
                        </div>
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
                            <div id="imagePreview" class="mt-4 hidden">
                                <h4 class="text-amber-900 font-semibold mb-2">Preview:</h4>
                                <div class="flex items-center justify-center p-2 bg-white rounded-lg border border-amber-300">
                                    <img id="preview" src="#" alt="Product preview" class="max-h-40 object-contain" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="products.php" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-amber-800 text-white">
                                <i class="fas fa-arrow-left mr-2 text-black"></i> Cancel
                            </a>
                            <button type="submit" class="wooden-cart-button inline-flex items-center px-4 py-2 bg-green-600 text-white">
                                <i class="fas fa-save mr-2 text-black"></i> Add Product
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
        $('.wood-card').each(function() {
            if (!$(this).find('.wooden-texture-footer').length) {
                $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
            }
        });
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
        $('.wood-card').addClass('fade-in');
        $('.wooden-cart-button').hover(
            function() {
                $(this).find('i').animate({ marginRight: '12px' }, 200);
            },
            function() {
                $(this).find('i').animate({ marginRight: '0.5rem' }, 200);
            }
        );
    });
    function showSpecificFields() {
        var category = document.getElementById('category').value;
        document.getElementById('socket_type_div').classList.add('hidden');
        document.getElementById('ram_type_div').classList.add('hidden');
        document.getElementById('wattage_div').classList.add('hidden');
        document.getElementById('form_factor_div').classList.add('hidden');
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
        }
    }
    </script>
</body>
</html>