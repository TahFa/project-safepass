<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>SafePass Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background: #f5f1fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            color: #6b3fc5;
        }

        .search-box {
            border: none;
            border-radius: 20px;
            padding: 18px 20px;
            background: white;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .vault-card {
            background: white;
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            height: 100%;
        }

        .vault-card:hover {
            transform: translateY(-5px);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0ddff;
            color: #b464ff;
            font-size: 24px;
        }

        .btn-main {
            background: linear-gradient(90deg, #b464ff, #ff9ab3);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 12px 20px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-edit {
            background: #b464ff;
            color: white;
            border: none;
            border-radius: 12px;
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }

        .btn-delete {
            background: #ff7f9f;
            color: white;
            border: none;
            border-radius: 12px;
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }

        .password-box {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .top-navbar {
            background: linear-gradient(90deg, #b464ff, #ff9ab3);
            padding: 18px 0;
            margin-bottom: 40px;
        }

        .navbar-brand {
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .user-box {
            color: white;
            font-weight: 600;
        }

        .logout-btn {
            background: white;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            color: #b464ff;
        }
    </style>

</head>

<body>

    <!-- NAVBAR -->
    <div class="top-navbar">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center">

                <a href="dashboard.php"
                    class="navbar-brand">

                    <i class="fa-solid fa-shield-halved"></i>
                    SafePass

                </a>

                <div class="d-flex align-items-center gap-3">

                    <div class="user-box">

                        <i class="fa-solid fa-circle-user"></i>

                        <?= $_SESSION['username'] ?>

                    </div>

                    <button
                        onclick="logoutUser()"
                        class="logout-btn">
                        Logout
                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="container pb-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

            <div>

                <h1 class="hero-title">
                    My Password Vault
                </h1>

                <p class="text-muted fs-5">
                    Semua password tersimpan aman dan terenkripsi.
                </p>

            </div>

            <a href="add_password.php"
                class="btn-main">

                <i class="fa-solid fa-circle-plus"></i>
                Add Password

            </a>

        </div>

        <!-- SEARCH -->
        <div class="row mb-5">

            <div class="col-lg-6">

                <input
                    type="text"
                    class="search-box"
                    id="searchInput"
                    placeholder="Search website or username..."
                    onkeyup="searchVault()">

            </div>

        </div>

        <!-- VAULT -->
        <div class="row g-4"
            id="vaultContainer"></div>

        <!-- PAGINATION -->
        <div
            class="d-flex justify-content-center mt-5"
            id="paginationContainer">
        </div>

    </div>

    <footer class="text-center py-4 text-muted">

        © <?php echo date('Y'); ?>
        SafePass — Secure Password Manager

    </footer>

    <script src="assets/js/crypto.js"></script>
    <script src="assets/js/auth.js"></script>
    <script src="assets/js/vault.js"></script>

    <script>
        // =========================
        // AUTO LOGOUT
        // =========================
        let timeout;

        function resetTimer() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                logoutUser();
                alert("Anda tidak aktif selama 5 menit");
            }, 300000);
        }

        document.addEventListener(
            "mousemove",
            resetTimer
        );

        document.addEventListener(
            "keypress",
            resetTimer
        );

        document.addEventListener(
            "click",
            resetTimer
        );


        // =========================
        // LOAD VAULT
        // =========================
        async function initVault() {

            await loadVaults();
        }


        // =========================
        // START
        // =========================
        window.onload = function() {

            resetTimer();

            initVault();
        }
    </script>

</body>

</html>