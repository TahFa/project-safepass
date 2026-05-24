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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Password - SafePass</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
body {background:#f5f1fb; font-family:'Segoe UI',sans-serif;}
.top-navbar{background:linear-gradient(90deg,#b464ff,#ff9ab3); padding:18px 0; margin-bottom:40px;}
.navbar-brand{color:white; font-size:24px; font-weight:700; text-decoration:none;}
.form-card{background:white; border-radius:30px; padding:35px; box-shadow:0 4px 15px rgba(0,0,0,0.05); max-width:720px; margin:auto;}
.page-title{font-size:42px; font-weight:800; color:#6b3fc5;}
.form-control,.form-select{border-radius:14px; padding:12px 14px;}
.btn-main{background:linear-gradient(90deg,#b464ff,#ff9ab3); color:white; border:none; border-radius:14px; padding:12px 20px; font-weight:600;}
.btn-back{background:#f2e8ff; color:#6b3fc5; border:none; border-radius:14px; padding:12px 20px; font-weight:600; text-decoration:none;}
.strength-bar{height:10px; border-radius:20px; background:#e5e5e5; overflow:hidden; margin-top:10px;}
.strength-fill{height:100%; width:0%; transition:0.3s;}
.strength-info{display:flex; justify-content:space-between; align-items:center; margin-top:6px; gap:10px; font-size:13px;}
#strengthLevel{font-weight:600; white-space:nowrap;}
#strengthDesc{flex:1; text-align:right;}
.password-wrapper{position:relative; display:flex; align-items:center; width:100%;}
.password-wrapper input{flex:1;}
.password-wrapper .btn-eye{position:absolute; right:15px; top:50%; transform:translateY(-50%); border:none; background:none; cursor:pointer; color:#555; font-size:1.3rem; z-index:2;}
.btn-generate{margin-top:10px; width:25%; background:linear-gradient(90deg,#4e54c8,#8f94fb); color:white; border:none; border-radius:14px; padding:12px; font-weight:600;}
</style>
</head>
<body>

<div class="top-navbar">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand"><i class="fa-solid fa-shield-halved"></i> SafePass</a>
    </div>
</div>

<div class="container pb-5">
    <div class="form-card">
        <h1 class="page-title mb-2">Add Password</h1>
        <p class="text-muted mb-4">Tambahkan data akun baru ke vault kamu.</p>

        <form autocomplete="off" onsubmit="event.preventDefault(); saveVault();">
            <div class="mb-3">
                <label class="form-label">Website / App</label>
                <input type="text" id="website" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username / Email</label>
                <input type="text" id="vaultUsername" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="vaultPassword" class="form-control" oninput="checkPasswordStrength()" required>
                    <button type="button" class="btn-eye" onclick="toggleFormPassword()"><i class="fa-solid fa-eye"></i></button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                <div class="strength-info">
                    <span id="strengthLevel" class="text-muted">Password</span>
                    <span id="strengthDesc" class="text-muted"></span>
                </div>
            </div>

            <div class="mb-3 d-flex align-items-end gap-2">
                <div class="flex-grow-1">
                    <label class="form-label">Panjang Password</label>
                    <select id="passwordLength" class="form-select">
                        <option value="12">12 Karakter</option>
                        <option value="16" selected>16 Karakter (Rekomendasi)</option>
                        <option value="20">20 Karakter</option>
                        <option value="24">24 Karakter</option>
                    </select>
                </div>
                <button type="button" class="btn-generate" onclick="fillGeneratedPassword()">Generate</button>
            </div>

            <div class="mb-4">
                <label class="form-label">Notes</label>
                <textarea id="notes" class="form-control" rows="4"></textarea>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="btn-main"><i class="fa-solid fa-floppy-disk"></i> Save Password</button>
                <a href="dashboard.php" class="btn-back">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/crypto.js"></script>
<script src="assets/js/vault.js"></script>
<script>
function toggleFormPassword(){ const input=document.getElementById("vaultPassword"); input.type=input.type==="password"?"text":"password"; }
function fillGeneratedPassword(){ const length=parseInt(document.getElementById("passwordLength").value); const generated=generatePassword(length); document.getElementById("vaultPassword").value=generated; checkPasswordStrength(generated); }
function checkPasswordStrength(password){
    const fill=document.getElementById("strengthFill"); const level=document.getElementById("strengthLevel"); const desc=document.getElementById("strengthDesc");
    let strength=0,missing=[];
    if(password.length>=8) strength++; else missing.push("min 8 karakter");
    if(/[A-Z]/.test(password)) strength++; else missing.push("huruf besar");
    if(/[0-9]/.test(password)) strength++; else missing.push("angka");
    if(/[^A-Za-z0-9]/.test(password)) strength++; else missing.push("simbol");
    desc.innerText = missing.length ? "Tambahkan "+missing.join(", ") : "Password aman";
    switch(strength){case 1: fill.style.width="25%"; fill.style.background="#ff4d4f"; level.innerText="Lemah"; break;
    case 2: fill.style.width="50%"; fill.style.background="#faad14"; level.innerText="Sedang"; break;
    case 3: fill.style.width="75%"; fill.style.background="#52c41a"; level.innerText="Kuat"; break;
    case 4: fill.style.width="100%"; fill.style.background="#389e0d"; level.innerText="Sangat Kuat"; break;
    default: fill.style.width="0%"; level.innerText="Password"; desc.innerText=""; break;}
}
document.getElementById("vaultPassword").addEventListener("input",function(){checkPasswordStrength(this.value);});
</script>
</body>
</html>