const modalEl = document.getElementById("Modal");
const btnPri = document.getElementById("btn_pri");

// Buscamos los botones dentro del modal
const openModal = () => {
    modalEl.classList.add("show");
    document.body.style.overflow = "hidden";
};

const closeModal = () => {
    modalEl.classList.remove("show");
    document.body.style.overflow = "";
};

// Evento para abrir
btnPri.onclick = openModal;

// Evento para cerrar buscando CUALQUIER click en botones de cerrar
modalEl.addEventListener("click", (e) => {
    // Si hace click en el fondo negro O en botones con clase de cerrar
    if (e.target === modalEl || e.target.classList.contains("btn-close") || e.target.classList.contains("btn-secondary")) {
        closeModal();
    }
});

// Cerrar con tecla Escape
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeModal();
});