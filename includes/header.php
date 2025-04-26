<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Hardware Store</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b',
                        danger: '#ef4444',
                        success: '#22c55e',
                        warning: '#f59e0b',
                        info: '#06b6d4',
                        wood: {
                            dark: '#714329',
                            medium: '#B08463',
                            light: '#B9937B',
                            tan: '#D0B9A7',
                            pale: '#B5A192'
                        }
                    }
                }
            }
        }
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <!-- Navigation -->
    <header class="wooden-header relative">
        <div class="wooden-texture absolute inset-0 z-0"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-wrap items-center justify-between py-4">
                <a class="store-logo flex items-center" href="index.php">
                    <div class="logo-icon mr-3">
                        <i class="fas fa-desktop text-2xl text-wood-dark"></i>
                    </div>
                    <div class="logo-text">
                        <span class="text-2xl font-bold text-wood-dark">PC Hardware</span>
                        <span class="store-badge ml-2 px-2 py-1 bg-wood-dark text-white text-xs uppercase rounded-md">Store</span>
                    </div>
                </a>
                <button id="menuButton" class="lg:hidden focus:outline-none wooden-button py-2 px-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
            </button>
                <div id="navMenu" class="hidden w-full lg:flex lg:items-center lg:w-auto">
                    <ul class="flex flex-col lg:flex-row lg:ml-auto space-y-2 lg:space-y-0 lg:space-x-1 mt-3 lg:mt-0">
                        <li>
                            <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="/pc-hardware-store/index.php">
                                <i class="fas fa-home mr-2"></i>Home
                        </a>
                    </li>
                        <li class="relative group">
                            <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300 cursor-pointer" id="productsDropdown">
                                <i class="fas fa-microchip mr-2"></i>Products
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <ul id="productsMenu" class="hidden wooden-dropdown absolute left-0 mt-2 w-64 py-2 z-10 rounded-lg shadow-xl">
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=CPU"><i class="fas fa-microchip mr-2"></i>Processors</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=GPU"><i class="fas fa-tv mr-2"></i>Graphics Cards</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=RAM"><i class="fas fa-memory mr-2"></i>Memory</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Storage"><i class="fas fa-hdd mr-2"></i>Storage</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Motherboard"><i class="fas fa-network-wired mr-2"></i>Motherboards</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Power+Supply"><i class="fas fa-plug mr-2"></i>Power Supplies</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=CPU+Cooler"><i class="fas fa-fan mr-2"></i>CPU Coolers</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Case"><i class="fas fa-server mr-2"></i>Cases</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Monitor"><i class="fas fa-display mr-2"></i>Monitors</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Peripherals"><i class="fas fa-keyboard mr-2"></i>Peripherals</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Networking"><i class="fas fa-network-wired mr-2"></i>Networking</a></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php?category=Accessories"><i class="fas fa-headset mr-2"></i>Accessories</a></li>
                                <li class="border-t border-wood-tan my-2"></li>
                                <li><a class="dropdown-item" href="/pc-hardware-store/index.php"><i class="fas fa-th-list mr-2"></i>All Products</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="/pc-hardware-store/contact.php">
                                <i class="fas fa-envelope mr-2"></i>Contact Us
                            </a>
                        </li>
                        <li>
                            <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="/pc-hardware-store/build_pc.php">
                                <i class="fas fa-tools mr-2"></i>Build Your PC
                            </a>
                        </li>
                    </ul>
                    <div class="border-t lg:border-t-0 lg:border-l border-wood-tan my-3 lg:my-0 lg:mx-4 lg:h-8"></div>
                    <ul class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-1 mt-3 lg:mt-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="relative group">
                                <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300 cursor-pointer" id="userDropdown">
                                    <i class="fas fa-user mr-2"></i><?= htmlspecialchars($_SESSION['username']) ?>
                                    <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </a>
                                <ul id="userMenu" class="hidden wooden-dropdown absolute right-0 mt-2 w-64 py-2 z-10 rounded-lg shadow-xl">
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="/pc-hardware-store/admin/index.php"><i class="fas fa-cog mr-2"></i>Admin Panel</a></li>
                                        <li class="border-t border-wood-tan my-2"></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="/pc-hardware-store/my_account.php"><i class="fas fa-user-circle mr-2"></i>My Account</a></li>
                                    <li><a class="dropdown-item" href="/pc-hardware-store/order_history.php"><i class="fas fa-history mr-2"></i>Order History</a></li>
                                    <li class="border-t border-wood-tan my-2"></li>
                                    <li><a class="dropdown-item" href="/pc-hardware-store/logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li>
                                <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="login.php">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                                </a>
                            </li>
                            <li>
                                <a class="nav-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="register.php">
                                    <i class="fas fa-user-plus mr-2"></i>Register
                                </a>
                        </li>
                    <?php endif; ?>
                        <li>
                            <a class="cart-link block px-4 py-2 rounded-lg hover:bg-wood-tan transition-all duration-300" href="/pc-hardware-store/cart.php">
                                <i class="fas fa-shopping-cart mr-2"></i>Cart
                            <?php
                            $cart_count = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $cart_count += $item['quantity'];
                                }
                            }
                            if ($cart_count > 0) {
                                    echo '<span class="cart-count">'.$cart_count.'</span>';
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </header>
    <script>
        $(document).ready(function() {
            $('.wooden-texture').each(function() {
                $(this).css({
                    'background-image': 'url("data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z\' fill=\'%235c4033\' fill-opacity=\'0.05\' fill-rule=\'evenodd\'/%3E%3C/svg%3E")',
                    'background-color': '#deb887'
                });
            });
            $('#menuButton').click(function() {
                $('#navMenu').slideToggle(300);
            });
            $('#productsDropdown').click(function(e) {
                e.stopPropagation();
                $('#productsMenu').fadeToggle(200);
                $('#userMenu').fadeOut(200);
            });
            $('#userDropdown').click(function(e) {
                e.stopPropagation();
                $('#userMenu').fadeToggle(200);
                $('#productsMenu').fadeOut(200);
            });
            $(document).click(function() {
                $('.wooden-dropdown').fadeOut(200);
            });
            $('.nav-link').hover(
                function() {
                    $(this).addClass('nav-hover');
                },
                function() {
                    $(this).removeClass('nav-hover');
                }
            );
            $('.dropdown-item').hover(
                function() {
                    $(this).addClass('dropdown-hover');
                    $(this).find('i').addClass('fa-bounce');
                },
                function() {
                    $(this).removeClass('dropdown-hover');
                    $(this).find('i').removeClass('fa-bounce');
                }
            );
            $('.store-logo').hover(
                function() {
                    $('.logo-icon i').addClass('fa-beat-fade');
                    $('.store-badge').addClass('badge-hover');
                },
                function() {
                    $('.logo-icon i').removeClass('fa-beat-fade');
                    $('.store-badge').removeClass('badge-hover');
                }
            );
            $('.cart-link').hover(
                function() {
                    $(this).find('i').addClass('fa-shake');
                },
                function() {
                    $(this).find('i').removeClass('fa-shake');
                }
            );
        });
    </script>
</body>
</html>