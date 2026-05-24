<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - SafePass</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-title">
                Create Account
            </div>

            <div class="auth-subtitle">
                Buat vault aman untuk semua password kamu.
            </div>

            <form
                autocomplete="off"
                onsubmit="event.preventDefault(); handleRegister();">

                <div class="mb-3">

                    <input
                        type="text"
                        id="username"
                        class="form-control"
                        placeholder="Username"
                        >

                </div>

                <div class="mb-3">

                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        placeholder="Master Password"
                        >

                </div>

                <button type="submit" class="auth-btn">
                    Register
                </button>

            </form>

            <div class="auth-link">

                Sudah punya akun?

                <a href="login.php">
                    Login
                </a>

            </div>

        </div>

    </div>

    <script src="assets/js/crypto.js"></script>

    <script src="assets/js/auth.js" defer></script>

</body>

</html>