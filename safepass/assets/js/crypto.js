// =========================
// CONFIG
// =========================
const ITERATIONS = 100000;

const KEY_LENGTH = 256;

const ALGORITHM = "AES-GCM";



// =========================
// GENERATE SALT
// =========================
function generateSalt() {

    return crypto.getRandomValues(
        new Uint8Array(16)
    );
}



// =========================
// GENERATE IV
// =========================
function generateIV() {

    return crypto.getRandomValues(
        new Uint8Array(12)
    );
}



// =========================
// BUFFER → BASE64
// =========================
function bufferToBase64(buffer) {

    return btoa(
        String.fromCharCode(
            ...new Uint8Array(buffer)
        )
    );
}



// =========================
// BASE64 → BUFFER
// =========================
function base64ToBuffer(base64) {

    return Uint8Array.from(
        atob(base64),
        c => c.charCodeAt(0)
    );
}



// =========================
// DERIVE KEY PBKDF2
// =========================
async function deriveKey(password, salt) {

    const encoder =
        new TextEncoder();

    const keyMaterial =
        await crypto.subtle.importKey(
            "raw",
            encoder.encode(password),
            {
                name: "PBKDF2"
            },
            false,
            ["deriveKey"]
        );

    return crypto.subtle.deriveKey(
    {
        name: "PBKDF2",
        salt: salt,
        iterations: ITERATIONS,
        hash: "SHA-256"
    },
    keyMaterial,
    {
        name: ALGORITHM,
        length: KEY_LENGTH
    },
    true,
    ["encrypt", "decrypt"]
);
}



// =========================
// GET MASTER PASSWORD
// =========================
function getMasterPassword() {

    return localStorage.getItem(
        "masterPassword"
    );
}



// =========================
// ENCRYPT VAULT
// =========================
async function encryptVault(data) {

    const password =
        getMasterPassword();

    if (!password) {

        throw new Error(
            "Master password tidak ditemukan"
        );
    }

    const salt =
        generateSalt();

    const iv =
        generateIV();

    const key =
        await deriveKey(
            password,
            salt
        );

    const encoder =
        new TextEncoder();

    const encrypted =
        await crypto.subtle.encrypt(
            {
                name: ALGORITHM,
                iv: iv
            },
            key,
            encoder.encode(data)
        );

    return {

        // cocok dengan database:
        // ciphertext | iv | auth_tag

        ciphertext:
            bufferToBase64(encrypted),

        iv:
            bufferToBase64(iv),

        salt:
            bufferToBase64(salt)
    };
}



// =========================
// DECRYPT VAULT
// =========================
async function decryptVault(vaultData) {

    const password =
        getMasterPassword();

    if (!password) {

        throw new Error(
            "Master password tidak ditemukan"
        );
    }

    if (
        !vaultData ||
        !vaultData.ciphertext ||
        !vaultData.iv ||
        !vaultData.salt
    ) {

        return "[]";
    }

    const salt =
        base64ToBuffer(
            vaultData.salt
        );

    const iv =
        base64ToBuffer(
            vaultData.iv
        );

    const encrypted =
        base64ToBuffer(
            vaultData.ciphertext
        );

    const key =
        await deriveKey(
            password,
            salt
        );

    const decrypted =
        await crypto.subtle.decrypt(
            {
                name: ALGORITHM,
                iv: iv
            },
            key,
            encrypted
        );

    return new TextDecoder()
        .decode(decrypted);
}



// =========================
// PASSWORD GENERATOR
// =========================
function generatePassword(
    length = 16
) {

    const chars =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+=";

    let password = "";

    const random =
        crypto.getRandomValues(
            new Uint32Array(length)
        );

    for (let i = 0; i < length; i++) {

        password +=
            chars[
                random[i] % chars.length
            ];
    }

    return password;
}