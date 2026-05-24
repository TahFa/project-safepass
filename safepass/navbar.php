<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm safepass-navbar px-4 py-3">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="dashboard.php">
            <i class="fa-solid fa-shield-halved"></i>
            SafePass
        </a>

        <div class="d-flex align-items-center gap-3 text-white">

            <?php if(isset($_SESSION['username'])): ?>

                <span class="fw-semibold">
                    <i class="fa-solid fa-circle-user"></i>
                    <?= $_SESSION['username']; ?>
                </span>

                <button onclick="logoutUser()" class="btn btn-light rounded-pill px-4 fw-semibold">
                    Logout
                </button>

            <?php endif; ?>

        </div>
    </div>
</nav>