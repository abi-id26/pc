<div class="card">
    <div class="card-body text-center">
        <div class="mb-3">
            <span class="bg-primary rounded-circle p-3 text-white d-inline-flex">
                <i class="fas fa-user fa-2x"></i>
            </span>
        </div>
        <h5><?= htmlspecialchars($_SESSION['username']) ?></h5>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="my_account.php">
                    <i class="fas fa-user me-2"></i> Account Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order_history.php">
                    <i class="fas fa-history me-2"></i> Order History
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="edit_account.php">
                    <i class="fas fa-cog me-2"></i> Account Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>