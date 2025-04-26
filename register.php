<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Username or email already exists";
    }
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }
}
?>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Register
        </div>
    </div>
    <div class="max-w-lg mx-auto">
        <div class="wood-card overflow-hidden fade-in">
            <div class="bg-amber-800 py-4 px-6 border-b border-amber-700">
                <h3 class="text-xl font-bold text-center text-amber-50">Create New Account</h3>
            </div>
            <div class="p-6 card-content">
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Please fix the following errors:</p>
                        <ul class="list-disc pl-5">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="wood-input-group mb-6">
                        <label for="username" class="wood-label flex items-center">
                            <i class="fas fa-user mr-2 text-amber-800"></i> Username
                        </label>
                        <input type="text" class="wood-input" id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" required>
                        <p class="text-xs text-amber-800 mt-1 ml-1">Username must be at least 4 characters long</p>
                    </div>
                    <div class="wood-input-group mb-6">
                        <label for="email" class="wood-label flex items-center">
                            <i class="fas fa-envelope mr-2 text-amber-800"></i> Email
                        </label>
                        <input type="email" class="wood-input" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="wood-input-group mb-6">
                            <label for="password" class="wood-label flex items-center">
                                <i class="fas fa-lock mr-2 text-amber-800"></i> Password
                            </label>
                            <input type="password" class="wood-input" id="password" name="password" required>
                            <p class="text-xs text-amber-800 mt-1 ml-1">Password must be at least 6 characters long</p>
                        </div>
                        <div class="wood-input-group mb-6">
                            <label for="confirm_password" class="wood-label flex items-center">
                                <i class="fas fa-lock mr-2 text-amber-800"></i> Confirm Password
                            </label>
                            <input type="password" class="wood-input" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="wood-checkbox mb-4">
                            <input type="checkbox" required>
                            <span class="checkmark"></span>
                            I agree to the <a href="#" class="text-amber-700 hover:underline">Terms of Service</a> and <a href="#" class="text-amber-700 hover:underline">Privacy Policy</a>
                        </label>
                    </div>
                    <div class="mb-6">
                        <button type="submit" class="wooden-cart-button w-full py-3">
                            <i class="fas fa-user-plus mr-2"></i>
                            <span class="button-text">Create Account</span>
                        </button>
                    </div>
                </form>
                <div class="text-center">
                    <div class="wooden-divider mb-4"></div>
                    <p class="text-amber-900">Already have an account? <a href="login.php" class="text-amber-700 font-bold hover:underline">Login here</a></p>
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
    $('#password').on('input', function() {
        let password = $(this).val();
        let strength = 0;
        if (password.length >= 6) {
            strength += 1;
        }
        if (password.match(/[A-Z]/)) {
            strength += 1;
        }
        if (password.match(/[0-9]/)) {
            strength += 1;
        }
        if (password.match(/[^A-Za-z0-9]/)) {
            strength += 1;
        }
        let strengthBar = '';
        for (let i = 0; i < 4; i++) {
            if (i < strength) {
                strengthBar += '<div class="h-2 w-full bg-green-600 rounded"></div>';
            } else {
                strengthBar += '<div class="h-2 w-full bg-gray-200 rounded"></div>';
            }
        }
        if ($('
            $(this).after('<div id="password-strength" class="grid grid-cols-4 gap-1 mt-2"></div>');
        }
        $('#password-strength').html(strengthBar);
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