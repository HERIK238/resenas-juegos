// ==========================================
// 1. GLOBAL OPEN FUNCTIONS (window.)
// ==========================================

// Open the Review modal (Plus button)
window.openModal = function() {
    const modalEl = document.getElementById("Modal");
    if (modalEl) {
        modalEl.classList.add("show");
        document.body.classList.add('no-scroll');
    }
};

// Open the Login modal
window.openLogin = function() {
    const modalLogin = document.getElementById("ModalLogin");
    if (modalLogin) {
        window.closeRegistro(); // Cerramos registro por si estaba abierto
        modalLogin.classList.add("show");
        document.body.classList.add('no-scroll');
    }
};

// Open the Register modal
window.openRegistro = function() {
    const modalRegistro = document.getElementById("ModalRegistro");
    if (modalRegistro) {
        window.closeLogin(); // Cerramos login para que aparezca el de registro
        modalRegistro.classList.add("show");
        document.body.classList.add('no-scroll');
    }
};

// ==========================================
// 2. GLOBAL CLOSE FUNCTIONS
// ==========================================

window.closeModal = function() {
    const modalEl = document.getElementById("Modal");
    if (modalEl) {
        modalEl.classList.remove("show");
        document.body.classList.remove('no-scroll');
    }
};

window.closeLogin = function() {
    const modalLogin = document.getElementById("ModalLogin");
    if (modalLogin) {
        modalLogin.classList.remove("show");
        document.body.classList.remove('no-scroll');
    }
};

window.closeRegistro = function() {
    const modalRegistro = document.getElementById("ModalRegistro");
    if (modalRegistro) {
        modalRegistro.classList.remove("show");
        document.body.classList.remove('no-scroll');
    }
};

// ==========================================
// 3. EVENT BINDINGS (CLICKS AND ESCAPE)
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
    // Connect the "+" button to the review modal
    const btnPri = document.getElementById("btn_pri");
    if (btnPri) {
        btnPri.onclick = window.openModal;
    }

    const uploadReviewButton = document.getElementById('btnUploadReview');
    if (uploadReviewButton) {
        uploadReviewButton.addEventListener('click', submitReview);
    }

    fillReviewOptions();

    // Close any modal when clicking the dark overlay
    window.addEventListener("click", (e) => {
        if (e.target.classList.contains("custom-modal")) {
            window.closeModal();
            window.closeLogin();
            window.closeRegistro();
        }
    });
});

// Close everything with the Escape key (very useful)
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        window.closeModal();
        window.closeLogin();
        window.closeRegistro();
    }
});

function fillReviewOptions() {
    const reviewGenreSelect = document.getElementById('reviewGenre');
    if (!reviewGenreSelect) {
        return;
    }

    fetch('../api/catalog.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                return;
            }

            if (Array.isArray(data.data.genres)) {
                // Remove duplicates by using a Set
                const uniqueGenres = [];
                const genreNames = new Set();
                
                data.data.genres.forEach(genre => {
                    if (!genreNames.has(genre.nombre)) {
                        genreNames.add(genre.nombre);
                        uniqueGenres.push(genre);
                    }
                });

                if (uniqueGenres.length > 0) {
                    reviewGenreSelect.innerHTML = '';
                    const placeholder = document.createElement('option');
                    placeholder.selected = true;
                    placeholder.disabled = true;
                    placeholder.value = '';
                    placeholder.textContent = 'Select game genre';
                    reviewGenreSelect.appendChild(placeholder);

                    uniqueGenres.forEach(genre => {
                        const opt = document.createElement('option');
                        opt.value = genre.nombre;
                        opt.textContent = genre.nombre;
                        reviewGenreSelect.appendChild(opt);
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading review options:', error);
        });
}

function submitReview() {
    const gameName = document.getElementById('reviewGameName')?.value.trim();
    const title = document.getElementById('reviewTitle')?.value.trim();
    const content = document.getElementById('reviewContent')?.value.trim();
    const genre = document.getElementById('reviewGenre')?.value;
    const rating = document.getElementById('reviewRating')?.value;

    if (!gameName || !title || !content || !genre) {
        alert('Please complete the game name, review title, review text, and genre.');
        return;
    }

    const formData = new FormData();
    formData.append('game_name', gameName);
    formData.append('titulo', title);
    formData.append('contenido', content);
    formData.append('genre', genre);
    if (rating) {
        formData.append('calificacion', rating);
    }

    fetch('../api/reviews.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Review submitted successfully.');
            window.closeModal();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'The review could not be saved.'));
        }
    })
    .catch(error => {
        console.error('Error submitting review:', error);
        alert('An error occurred while submitting the review.');
    });
}

// open user settings

window.openUserConfig = function () {
    try {
        window.location.href = "../views/settings.php";
    } catch (error) {
        console.error("Error opening user settings:", error);
    }
}

// open user reviews
window.openUserReviews = function () {
    try {
        window.location.href = "../views/reviews.php";
    } catch (error) {
        console.error("Error opening user reviews:", error);
    }
}

// open user recommendations
window.openUserRecommendations = function () {
    try {
        window.location.href = "../views/recommendations.php";
    } catch (error) {
        console.error("Error al abrir recomendaciones del usuario:", error);
    }
}

window.openUserData = function () {
    try {
        window.location.href = "../views/data.php";
    } catch (error) {
        console.error("Error al abrir datos del usuario:", error);
    }
}