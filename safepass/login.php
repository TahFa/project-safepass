<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SafePass</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-title">
                Welcome Back
            </div>

            <div class="auth-subtitle">
                Login untuk membuka vault kamu.
            </div>

            <form
                autocomplete="off"
                onsubmit="event.preventDefault(); handleLogin();">

                <div class="mb-3">

                    <input
                        type="text"
                        id="username"
                        class="form-control"
                        placeholder="Username"
                        required>

                </div>

                <div class="mb-3">

                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        placeholder="Master Password"
                        required>

                </div>

                <button type="submit" class="auth-btn">
                    Login
                </button>

            </form>

            <div class="auth-link">

                Belum punya akun?

                <a href="registrasi.php">
                    Register
                </a>

            </div>

        </div>

    </div>

    <script src="assets/js/crypto.js"></script>

    <script src="assets/js/auth.js" defer></script>

</body>

</html>