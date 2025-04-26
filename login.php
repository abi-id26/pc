<?php
require_once 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
require_once 'includes/header.php';
?>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Login
        </div>
    </div>
    <div class="max-w-md mx-auto">
        <div class="wood-card overflow-hidden fade-in">
            <div class="bg-amber-800 py-4 px-6 border-b border-amber-700">
                <h3 class="text-xl font-bold text-center text-amber-50">Login to Your Account</h3>
            </div>
            <div class="p-6 card-content">
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p><i class="fas fa-exclamation-circle mr-2"></i> <?= $error ?></p>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="wood-input-group mb-6">
                        <label for="username" class="wood-label flex items-center">
                            <i class="fas fa-user mr-2 text-amber-800"></i> Username
                        </label>
                        <input type="text" class="wood-input" id="username" name="username" required>
                    </div>
                    <div class="wood-input-group mb-6">
                        <label for="password" class="wood-label flex items-center">
                            <i class="fas fa-lock mr-2 text-amber-800"></i> Password
                        </label>
                        <input type="password" class="wood-input" id="password" name="password" required>
                    </div>
                    <div class="mb-6">
                        <button type="submit" class="wooden-cart-button w-full py-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="button-text">Login</span>
                        </button>
                    </div>
                </form>
                <div class="text-center">
                    <div class="wooden-divider mb-4"></div>
                    <p class="text-amber-900">Don't have an account? <a href="register.php" class="text-amber-700 font-bold hover:underline">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    $('.wood-input').focus(function() {
        $(this).parents('.wood-input-group').addClass('focused');
    }).blur(function() {
        $(this).parents('.wood-input-group').removeClass('focused');
    });
    $('.wooden-cart-button').hover(
        function() {
            $(this).find('i').addClass('fa-bounce');
        },
        function() {
            $(this).find('i').removeClass('fa-bounce');
        }
    );
});
</script>
<?php require_once 'includes/footer.php'; ?>