# SafePass - Password Manager Terenkripsi

## 1. Deskripsi Proyek
SafePass adalah aplikasi web untuk menyimpan password user secara aman. Semua password dienkripsi AES-256-GCM di sisi client menggunakan master password, sehingga server tidak pernah menyimpan password asli (zero-knowledge).

---

## 2. Persyaratan Sistem
- PHP >= 8.0
- MySQL/MariaDB
- Browser modern (Chrome, Firefox, Edge) yang mendukung Web Crypto API
- Web server lokal (XAMPP, MAMP, LAMP)

---

## 3. Instalasi & Setup

1. Salin folder proyek ke web root/ke htdocs (xampp) -> htdocs/safepass/
2. Jalankan MySQL / MariaDB.
3. Buat database baru: db_safepass
4. Import file database dari folder database:db_safepass.sql
5. Periksa konfigurasi `koneksi.php` sesuai username/password MySQL lokal.
6. Buka browser: http://localhost/safepass/

---

## 4. Cara Menjalankan Aplikasi

1. **Registrasi:** buka `registrasi.php`, buat akun baru.  
2. **Login:** buka `login.php`, masukkan username dan master password.  
3. **Dashboard:** lihat semua password, search, copy, edit, delete.  
4. **Add Password:** buka `add_password.php`, input data baru, bisa generate password, cek strength.  
5. **Edit Password:** buka `edit_password.php`, update vault.  
6. **Delete Password:** hapus entry dari dashboard.  
7. **Logout / Auto-logout:** manual via tombol logout atau otomatis setelah 5 menit tidak aktif.

---


## 5. Video Demonstrasi & Pengujian

**Link Youtube:** [link youtube]

---

## 6. Fitur Keamanan

- Vault dienkripsi AES-256-GCM di browser, server hanya menyimpan `ciphertext`, `iv`, `salt`.
- Master password tidak dikirim plaintext ke server.
- PBKDF2 digunakan untuk derivasi kunci dengan salt unik per user.
- Auto-logout aktif di semua halaman untuk mencegah akses tidak sah.
- Password generator dengan panjang dan kompleksitas bisa dikonfigurasi.

---

## 7. Catatan

- Kode siap dijalankan secara lokal tanpa deploy ke server publik.  
- Aplikasi menggunakan zero-knowledge architecture: server tidak pernah tahu isi vault.  
- Pastikan browser mendukung Web Crypto API agar enkripsi/dekripsi berjalan.