// ==========================================
// 1. FUNCIONES GLOBALES DE APERTURA (window.)
// ==========================================

// Abrir el modal de Reseñas (Botón +)
window.openModal = function() {
    const modalEl = document.getElementById("Modal");
    if (modalEl) {
        modalEl.style.display = "flex";
        modalEl.classList.add("show");
        document.body.style.overflow = "hidden";
    }
};

// Abrir el modal de Inicio de Sesión
window.openLogin = function() {
    const modalLogin = document.getElementById("ModalLogin");
    if (modalLogin) {
        window.closeRegistro(); // Cerramos registro por si estaba abierto
        modalLogin.style.display = "flex";
        modalLogin.classList.add("show");
        document.body.style.overflow = "hidden";
    }
};

// Abrir el modal de Registro
window.openRegistro = function() {
    const modalRegistro = document.getElementById("ModalRegistro");
    if (modalRegistro) {
        window.closeLogin(); // Cerramos login para que aparezca el de registro
        modalRegistro.style.display = "flex";
        modalRegistro.classList.add("show");
        document.body.style.overflow = "hidden";
    }
};

// ==========================================
// 2. FUNCIONES GLOBALES DE CIERRE
// ==========================================

window.closeModal = function() {
    const modalEl = document.getElementById("Modal");
    if (modalEl) {
        modalEl.style.display = "none";
        modalEl.classList.remove("show");
        document.body.style.overflow = "";
    }
};

window.closeLogin = function() {
    const modalLogin = document.getElementById("ModalLogin");
    if (modalLogin) {
        modalLogin.style.display = "none";
        modalLogin.classList.remove("show");
        document.body.style.overflow = "";
    }
};

window.closeRegistro = function() {
    const modalRegistro = document.getElementById("ModalRegistro");
    if (modalRegistro) {
        modalRegistro.style.display = "none";
        modalRegistro.classList.remove("show");
        document.body.style.overflow = "";
    }
};

// ==========================================
// 3. CONEXIÓN DE EVENTOS (CLICS Y ESCAPE)
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
    // Conectamos el botón "+" con la función de abrir reseña
    const btnPri = document.getElementById("btn_pri");
    if (btnPri) {
        btnPri.onclick = window.openModal;
    }

    // Cerrar cualquier modal al hacer clic en el fondo oscuro
    window.addEventListener("click", (e) => {
        if (e.target.classList.contains("custom-modal")) {
            window.closeModal();
            window.closeLogin();
            window.closeRegistro();
        }
    });
});

// Cerrar todo con la tecla Escape (Súper útil)
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        window.closeModal();
        window.closeLogin();
        window.closeRegistro();
    }
});

// abrir configuración del usuario

window.openUserConfig = function () {
    try {
        window.location.href = "../views/settings.php";
    } catch (error) {
        console.error("Error al abrir configuración del usuario:", error);
    }
}