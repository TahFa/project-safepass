let vaultData = [];
let filteredData = [];

// Pagination
let currentPage = 1;
const itemsPerPage = 3;

// =========================
// LOAD VAULT
// =========================
async function loadVaults() {

    try {

        const response = await fetch(
            "api/get_vault.php",
            {
                credentials: "same-origin"
            }
        );

        const result =
            await response.json();

        if (
            !result.success ||
            !result.data
        ) {

            vaultData = [];
            filteredData = [];

            renderVaults();

            return;
        }

        const decrypted =
            await decryptVault(
                result.data
            );

        vaultData =
            JSON.parse(decrypted);

        filteredData =
            [...vaultData];

        localStorage.setItem(
            "vaultData",
            JSON.stringify(vaultData)
        );

        renderVaults();

    } catch (error) {

        console.error(error);
    }
}


// =========================
// SAVE VAULT
// =========================
async function saveVault() {

    const website =
        document.getElementById(
            "website"
        ).value.trim();

    const username =
        document.getElementById(
            "vaultUsername"
        ).value.trim();

    const password =
        document.getElementById(
            "vaultPassword"
        ).value;

    const notes =
        document.getElementById(
            "notes"
        ).value.trim();


    // VALIDASI
    if (
        !website ||
        !username ||
        !password
    ) {

        alert(
            "Semua field wajib diisi"
        );

        return;
    }


    try {

        let currentVault = [];

        // AMBIL DATA LAMA
        const response =
            await fetch(
                "api/get_vault.php",
                {
                    credentials:
                        "same-origin"
                }
            );

        const result =
            await response.json();


        // JIKA ADA DATA
        if (
            result.success &&
            result.data
        ) {

            const decrypted =
                await decryptVault(
                    result.data
                );

            currentVault =
                JSON.parse(decrypted);
        }


        // TAMBAH DATA BARU
        currentVault.push({

            website: website,
            username: username,
            password: password,
            notes: notes

        });


        // ENCRYPT
        const encrypted =
            await encryptVault(
                JSON.stringify(
                    currentVault
                )
            );


        // SAVE
        const saveResponse =
            await fetch(
                "api/save_vault.php",
                {
                    method: "POST",

                    credentials:
                        "same-origin",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify(encrypted)
                }
            );


        const saveResult =
            await saveResponse.json();


        // SUCCESS
        if (saveResult.success) {

            alert(
                "Vault berhasil disimpan"
            );

            window.location.href =
                "dashboard.php";

        } else {

            alert(
                saveResult.message
            );
        }

    } catch (error) {

        console.error(error);

        alert(
            "Gagal menyimpan vault"
        );
    }
}


// =========================
// UPDATE VAULT
// =========================
async function updateVault() {

    const index =
        document.getElementById(
            "vaultIndex"
        ).value;

    const website =
        document.getElementById(
            "website"
        ).value.trim();

    const username =
        document.getElementById(
            "vaultUsername"
        ).value.trim();

    const password =
        document.getElementById(
            "vaultPassword"
        ).value;

    const notes =
        document.getElementById(
            "notes"
        ).value.trim();


    // VALIDASI
    if (
        !website ||
        !username ||
        !password
    ) {

        alert(
            "Semua field wajib diisi"
        );

        return;
    }


    try {

        // =========================
        // AMBIL DATA TERBARU DARI API
        // =========================
        const getResponse =
            await fetch(
                "api/get_vault.php",
                {
                    credentials:
                        "same-origin"
                }
            );

        const getResult =
            await getResponse.json();

        let currentVault = [];

        if (
            getResult.success &&
            getResult.data
        ) {

            const decrypted =
                await decryptVault(
                    getResult.data
                );

            currentVault =
                JSON.parse(decrypted);
        }


        // VALIDASI INDEX
        if (
            !currentVault[index]
        ) {

            alert(
                "Vault tidak ditemukan"
            );

            return;
        }


        // =========================
        // UPDATE DATA
        // =========================
        currentVault[index] = {

            website: website,
            username: username,
            password: password,
            notes: notes
        };


        // =========================
        // ENCRYPT DATA BARU
        // =========================
        const encrypted =
            await encryptVault(
                JSON.stringify(
                    currentVault
                )
            );


        // =========================
        // SAVE KE SERVER
        // =========================
        const response =
            await fetch(
                "api/update_vault.php",
                {
                    method: "POST",

                    credentials:
                        "same-origin",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify(
                        encrypted
                    )
                }
            );


        const result =
            await response.json();


        // =========================
        // SUCCESS
        // =========================
        if (result.success) {

            localStorage.setItem(
                "vaultData",
                JSON.stringify(
                    currentVault
                )
            );

            alert(
                "Vault berhasil diupdate"
            );

            window.location.href =
                "dashboard.php";

        } else {

            alert(
                result.message
            );
        }

    } catch (error) {

        console.error(error);

        alert(
            "Gagal update vault"
        );
    }
}


// =========================
// DELETE VAULT
// =========================
async function deleteVault(index) {

    if (
        !confirm(
            "Hapus vault ini?"
        )
    ) return;

    try {

        // =========================
        // AMBIL DATA TERBARU
        // =========================
        const getResponse =
            await fetch(
                "api/get_vault.php",
                {
                    credentials:
                        "same-origin"
                }
            );

        const getResult =
            await getResponse.json();

        let currentVault = [];

        if (
            getResult.success &&
            getResult.data
        ) {

            const decrypted =
                await decryptVault(
                    getResult.data
                );

            currentVault =
                JSON.parse(decrypted);
        }


        // =========================
        // VALIDASI INDEX
        // =========================
        if (
            !currentVault[index]
        ) {

            alert(
                "Vault tidak ditemukan"
            );

            return;
        }


        // =========================
        // HAPUS DATA
        // =========================
        currentVault.splice(
            index,
            1
        );


        // =========================
        // ENCRYPT ULANG
        // =========================
        const encrypted =
            await encryptVault(
                JSON.stringify(
                    currentVault
                )
            );


        // =========================
        // SAVE SERVER
        // =========================
        const response =
            await fetch(
                "api/delete_vault.php",
                {
                    method: "POST",

                    credentials:
                        "same-origin",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify(
                        encrypted
                    )
                }
            );


        const result =
            await response.json();


        // =========================
        // SUCCESS
        // =========================
        if (result.success) {

            vaultData =
                [...currentVault];

            filteredData =
                [...currentVault];

            localStorage.setItem(
                "vaultData",
                JSON.stringify(
                    currentVault
                )
            );


            // =========================
            // FIX PAGINATION
            // =========================
            const totalPages =
                Math.ceil(
                    filteredData.length /
                    itemsPerPage
                );

            if (currentPage > totalPages) {

                currentPage =
                    totalPages || 1;
            }


            renderVaults();

            alert(
                "Vault berhasil dihapus"
            );

        } else {

            alert(
                result.message
            );
        }

    } catch (error) {

        console.error(error);

        alert(
            "Gagal hapus vault"
        );
    }
}


// =========================
// SEARCH
// =========================
function searchVault() {

    const keyword =
        document
        .getElementById("searchInput")
        .value
        .toLowerCase();

    filteredData =
        vaultData.filter(vault => {

            return (

                vault.website
                .toLowerCase()
                .includes(keyword)

                ||

                vault.username
                .toLowerCase()
                .includes(keyword)

                ||

                (
                    vault.notes &&
                    vault.notes
                    .toLowerCase()
                    .includes(keyword)
                )
            );
        });

    currentPage = 1;

    renderVaults();
}


// =========================
// RENDER VAULT
// =========================
function renderVaults() {

    const container =
        document.getElementById(
            "vaultContainer"
        );

    const pagination =
        document.getElementById(
            "paginationContainer"
        );

    if (!container) return;

    container.innerHTML = "";

    if (pagination) {
        pagination.innerHTML = "";
    }

    if (filteredData.length === 0) {

        container.innerHTML = `
            <div class="text-center w-100">
                Vault kosong
            </div>
        `;

        return;
    }


    // =========================
    // PAGINATION
    // =========================
    const startIndex =
        (currentPage - 1) *
        itemsPerPage;

    const endIndex =
        startIndex +
        itemsPerPage;

    const paginatedData =
        filteredData.slice(
            startIndex,
            endIndex
        );


    // =========================
    // RENDER CARD
    // =========================
    paginatedData.forEach((vault) => {

        const originalIndex =
            vaultData.findIndex(v =>
                v.website === vault.website &&
                v.username === vault.username &&
                v.password === vault.password
            );

        container.innerHTML += `
        <div class="col-lg-4 col-md-6">

            <div class="vault-card">

                <div class="icon-circle mb-3">
                    <i class="fa-solid fa-globe"></i>
                </div>

                <h4>${vault.website}</h4>

                <p class="text-muted mb-1">
                    Username
                </p>

                <p>${vault.username}</p>

                <p class="text-muted mb-1 mt-2">
                    Password
                </p>

                <div class="password-box">

                    <input
                        type="password"
                        class="form-control form-control-sm"
                        value="${vault.password}"
                        id="pass-${originalIndex}"
                        readonly
                    >

                    <button
                        class="btn btn-light btn-sm"
                        onclick="togglePassword(${originalIndex})"
                    >
                        👁
                    </button>

                    <button
                        class="btn btn-light btn-sm"
                        onclick="copyPassword(${originalIndex})"
                    >
                        📋
                    </button>

                </div>

                ${
                    vault.notes
                    ? `
                    <p class="text-muted mb-1 mt-3">
                        Notes
                    </p>

                    <p class="small">
                        ${vault.notes}
                    </p>
                    `
                    : ""
                }

                <div class="row mt-3">

                    <div class="col-6">

                        <button
                            class="btn-edit"
                            onclick="window.location.href='edit_password.php?index=${originalIndex}'"
                        >
                            Edit
                        </button>

                    </div>

                    <div class="col-6">

                        <button
                            class="btn-delete"
                            onclick="deleteVault(${originalIndex})"
                        >
                            Delete
                        </button>

                    </div>

                </div>

            </div>

        </div>`;
    });


    // =========================
    // RENDER PAGINATION
    // =========================
    renderPagination();
}


// =========================
// RENDER PAGINATION
// =========================
function renderPagination() {

    const pagination =
        document.getElementById(
            "paginationContainer"
        );

    if (!pagination) return;

    pagination.innerHTML = "";

    const totalPages =
        Math.ceil(
            filteredData.length /
            itemsPerPage
        );

    if (totalPages <= 1) {
        return;
    }

    // =========================
    // PREVIOUS BUTTON
    // =========================
    pagination.innerHTML += `
        <button
            class="btn btn-light me-2"
            onclick="changePage(${currentPage - 1})"
            ${currentPage === 1 ? "disabled" : ""}
        >
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    `;


    // =========================
    // PAGE NUMBERS
    // =========================
    for (
        let i = 1;
        i <= totalPages;
        i++
    ) {

        pagination.innerHTML += `
            <button
                class="
                    btn
                    ${i === currentPage
                        ? "btn-primary"
                        : "btn-light"}
                    me-2
                "
                onclick="changePage(${i})"
            >
                ${i}
            </button>
        `;
    }


    // =========================
    // NEXT BUTTON
    // =========================
    pagination.innerHTML += `
        <button
            class="btn btn-light"
            onclick="changePage(${currentPage + 1})"
            ${currentPage === totalPages ? "disabled" : ""}
        >
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    `;
}


// =========================
// CHANGE PAGE
// =========================
function changePage(page) {

    currentPage = page;

    renderVaults();
}


// =========================
// TOGGLE PASSWORD
// =========================
function togglePassword(index) {

    const input =
        document.getElementById(
            "pass-" + index
        );

    input.type =
        input.type === "password"   
        ? "text"
        : "password";
}


// =========================
// COPY PASSWORD
// =========================
function copyPassword(index) {

    const input =
        document.getElementById(
            "pass-" + index
        );

    navigator.clipboard.writeText(
        input.value
    );

    alert("Password copied!");
}