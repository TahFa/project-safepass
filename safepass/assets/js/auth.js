// SESSION KEY
let sessionKey = null;

// =========================
// REGISTER
// =========================
async function handleRegister() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!username || !password) {
        alert("Username dan password wajib diisi");
        return;
    }

    try {
        // GENERATE SALT
        const salt = generateSalt();

        // DERIVE KEY
        const key = await deriveKey(password, salt);

        // EXPORT RAW KEY
        const rawKey = await crypto.subtle.exportKey("raw", key);

        // PASSWORD HASH
        const passwordHash = bufferToBase64(rawKey);

        // PAYLOAD
        const payload = {
            username: username,
            password_hash: passwordHash,
            salt: bufferToBase64(salt),
            iterations: ITERATIONS
        };

        // REGISTER API
        const response = await fetch("api/register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.success) {
            alert("Registrasi berhasil!");
            window.location.href = "login.php";
        } else {
            alert(result.message);
        }

    } catch (error) {
        console.error(error);
        alert("Error saat register");
    }
}

// =========================
// LOGIN
// =========================
async function handleLogin() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!username || !password) {
        alert("Username dan password wajib diisi");
        return;
    }

    try {
        // GET USER DATA (untuk ambil salt dan iterations)
        const response = await fetch("api/get_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username })
        });

        const result = await response.json();

        if (!result.success) {
            alert("User tidak ditemukan");
            return;
        }

        const user = result.data;

        // DERIVE KEY DARI MASTER PASSWORD + SALT
        const salt = base64ToBuffer(user.salt);
        const key = await deriveKey(password, salt);

        // EXPORT RAW KEY → BASE64
        const rawKey = await crypto.subtle.exportKey("raw", key);
        const computedHash = bufferToBase64(rawKey);

        // VERIFIKASI PASSWORD DI CLIENT
        if (computedHash !== user.password_hash) {
            alert("Password salah!");
            return;
        }

        // SESSION KEY di browser
        sessionKey = key;

        // SIMPAN LOGIN DI SESSION STORAGE
        sessionStorage.setItem("login", "true");
        sessionStorage.setItem("username", username);

        // SIMPAN MASTER PASSWORD sementara di localStorage (untuk dekripsi vault)
        localStorage.setItem("masterPassword", password);

        // KIRIM HASH KE SERVER untuk buat PHP session
        const sessionResponse = await fetch("api/login_session.php", {
            method: "POST",
            credentials: "same-origin",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                username,
                password_hash: computedHash // Hanya hash PBKDF2
            })
        });

        const sessionResult = await sessionResponse.json();

        if (!sessionResult.success) {
            alert("Gagal membuat session");
            return;
        }

        // REDIRECT KE DASHBOARD
        window.location.href = "dashboard.php";

    } catch (error) {
        console.error(error);
        alert("Error saat login");
    }
}

// =========================
// LOGOUT
// =========================
async function logoutUser() {
    try {
        await fetch("api/logout.php", { credentials: "same-origin" });

        // RESET SESSION KEY
        sessionKey = null;

        // CLEAR STORAGE
        sessionStorage.clear();
        localStorage.removeItem("masterPassword");
        localStorage.removeItem("vaultData");

        // REDIRECT
        window.location.href = "login.php";

    } catch (error) {
        console.error(error);
        alert("Gagal logout");
    }
}

// =========================
// GET SESSION KEY
// =========================
function getSessionKey() {
    return sessionKey;
}