<?php
require_once 'includes/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];
    
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = "Username must be between 3 and 30 characters";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // Validate password if provided
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    // Check if username or email is already taken by another user
    if (empty($errors)) {
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $check_stmt->execute([$username, $email, $_SESSION['user_id']]);
        if ($check_stmt->rowCount() > 0) {
            $errors[] = "Username or email is already taken";
        }
    }
    
    // If no errors, proceed with update
    if (empty($errors)) {
        // Start with basic update for username and email
        $sql = "UPDATE users SET username = ?, email = ?";
        $params = [$username, $email];
        
        // If password is provided, update it too
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $hashed_password;
        }
        
        // Add WHERE clause and user ID
        $sql .= " WHERE id = ?";
        $params[] = $_SESSION['user_id'];
        
        // Execute the update query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Update session with new username if changed
        $_SESSION['username'] = $username;
        
        $_SESSION['success'] = "Account updated successfully!";
        header("Location: my_account.php");
        exit();
    }
}

// Include header after all redirects
require_once 'includes/header.php';

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item">
            <a href="my_account.php">My Account</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Edit Account
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Account Sidebar -->
        <div class="w-full md:w-1/4">
            <div class="wood-card overflow-hidden">
                <div class="wooden-texture-overlay"></div>
                <div class="p-5 text-center relative z-10">
                    <div class="mb-4 mx-auto">
                        <span class="bg-amber-800 rounded-full p-4 text-white inline-flex items-center justify-center">
                            <i class="fas fa-user fa-2x"></i>
                        </span>
                    </div>
                    <h5 class="text-xl font-bold text-amber-900 mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                    <p class="text-amber-700">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                    <hr class="my-4 border-amber-200">
                    <ul class="nav flex-col space-y-2">
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-amber-100 transition-colors rounded" href="my_account.php">
                                <i class="fas fa-user mr-2"></i> Account Overview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-amber-100 transition-colors rounded" href="order_history.php">
                                <i class="fas fa-history mr-2"></i> Order History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wooden-cart-button w-full py-2 px-4 flex items-center" href="edit_account.php">
                                <i class="fas fa-cog mr-2"></i> Account Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="wood-nav-link w-full py-2 px-4 flex items-center hover:bg-red-100 text-red-700 transition-colors rounded" href="logout.php">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4">
            <div class="wood-card overflow-hidden">
                <div class="wooden-texture-overlay"></div>
                <div class="relative z-10">
                    <div class="p-4 bg-amber-800 text-white">
                        <h4 class="text-xl font-bold mb-0">Edit Account</h4>
                    </div>
                    <div class="p-5">
                        <?php if (!empty($errors)): ?>
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                                <ul class="list-disc ml-5">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-6">
                                <label for="username" class="wood-label block text-amber-900 font-medium mb-2">Username</label>
                                <input type="text" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" 
                                       id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-6">
                                <label for="email" class="wood-label block text-amber-900 font-medium mb-2">Email</label>
                                <input type="email" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" 
                                       id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="mb-6">
                                <label for="password" class="wood-label block text-amber-900 font-medium mb-2">New Password (leave blank to keep current)</label>
                                <input type="password" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" 
                                       id="password" name="password">
                                <p class="text-sm text-amber-700 mt-1">Password must be at least 6 characters long</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <button type="submit" class="wooden-cart-button px-4 py-2 inline-flex items-center bg-amber-800">
                                    <i class="fas fa-save mr-2"></i> Save Changes
                                </button>
                                <a href="my_account.php" class="wooden-cart-button px-4 py-2 inline-flex items-center bg-amber-100 hover:bg-amber-200 text-amber-800">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>