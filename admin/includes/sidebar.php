<div class="wood-card w-full md:w-64 shadow-lg relative overflow-hidden">
    <div class="wooden-texture-overlay"></div>
    <div class="wooden-texture-footer absolute inset-0 z-0"></div>
    <div class="sticky top-0 relative z-10">
        <div class="p-4 bg-amber-800 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold mb-0 flex items-center text-black">
                <i class="fas fa-tools mr-2"></i> Admin Panel
            </h2>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <div class="text-center">
                    <div class="mb-3 mx-auto">
                        <span class="bg-amber-700 rounded-full p-3 text-white inline-flex items-center justify-center">
                            <i class="fas fa-user-shield fa-lg text-black"></i>
                        </span>
                    </div>
                    <h5 class="text-lg font-bold text-amber-900 mb-1">Administrator</h5>
                    <div class="wooden-divider my-3"></div>
                </div>
            </div>
            <nav>
                <ul class="nav flex-col space-y-1">
                    <li class="nav-item">
                        <a class="wood-nav-link w-full py-3 px-4 flex items-center hover:bg-amber-100 transition-colors rounded <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active-nav-link' : '' ?>" href="index.php">
                            <i class="fas fa-tachometer-alt mr-3 text-amber-800"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="wood-nav-link w-full py-3 px-4 flex items-center hover:bg-amber-100 transition-colors rounded <?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active-nav-link' : '' ?>" href="products.php">
                            <i class="fas fa-boxes mr-3 text-amber-800"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="wood-nav-link w-full py-3 px-4 flex items-center hover:bg-amber-100 transition-colors rounded <?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active-nav-link' : '' ?>" href="orders.php">
                            <i class="fas fa-clipboard-list mr-3 text-amber-800"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="wood-nav-link w-full py-3 px-4 flex items-center hover:bg-amber-100 transition-colors rounded <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active-nav-link' : '' ?>" href="users.php">
                            <i class="fas fa-users mr-3 text-amber-800"></i> Users
                        </a>
                    </li>
                    <div class="wooden-divider my-3"></div>
                    <li class="nav-item">
                        <a class="wooden-cart-button w-full py-3 px-4 flex items-center justify-center my-2" href="../index.php">
                            <i class="fas fa-store mr-2"></i> View Store
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="wood-nav-link w-full py-3 px-4 flex items-center hover:bg-red-100 text-red-700 transition-colors rounded" href="../logout.php">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<style>
    .active-nav-link {
        background-color:
        color: white;
    }
    .active-nav-link i {
        color: white !important;
    }
    .nav-item a:hover i {
        transform: translateX(3px);
        transition: transform 0.2s ease;
    }
</style>
<script>
$(document).ready(function() {
    $('.wood-nav-link').hover(
        function() {
            $(this).find('i').css('transform', 'translateX(3px)');
        },
        function() {
            $(this).find('i').css('transform', 'translateX(0)');
        }
    );
});
</script>