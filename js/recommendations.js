document.addEventListener('DOMContentLoaded', () => {
    loadRecommendations();
    loadCatalog();

    const searchInput = document.getElementById('catalogSearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            loadCatalog(searchInput.value.trim());
        });
    }
});

window.openUserHome = function () {
    try {
        window.location.href = "../views/dashboard.php";
    } catch (error) {
        console.error("Error opening home page:", error);
    }
}


window.openUserConfig = function () {
    try {
        window.location.href = "../views/settings.php";
    }catch (error) {
        console.log("error al abrir la configuracion del usuario:", error)
    }
}

window.openUserReviews = function () {
    try {
        window.location.href = "../views/reviews.php";
    } catch (error) {
        console.error("Error opening user reviews:", error);
    }
}

window.openUserData = function () {
    try {
        window.location.href = "../views/data.php";
    } catch (error) {
        console.error("Error al abrir datos del usuario:", error);
    }
}

function loadRecommendations() {
    const container = document.getElementById('recommendationsList');
    if (!container) {
        return;
    }
    container.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

    fetch('../api/recommendations.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                const warn = document.createElement('div');
                warn.className = 'alert alert-warning';
                warn.textContent = data.message || 'Could not load recommendations.';
                container.replaceChildren(warn);
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No recommendations available.</div>';
                return;
            }

            const template = document.getElementById('recommendationCardTemplate');
            container.innerHTML = '';
            data.data.forEach(game => {
                const clone = template.content.cloneNode(true);
                const img = clone.querySelector('.game-cover');
                if (game.portada_url) {
                    img.src = game.portada_url;
                    img.alt = game.titulo;
                } else {
                    img.remove();
                }
                clone.querySelector('.game-title').textContent = game.titulo;
                clone.querySelector('.game-description').textContent = game.descripcion ?? 'Sin descripción';
                clone.querySelector('.game-date').textContent = `Creado el ${new Date(game.created_at).toLocaleDateString()}`;
                container.appendChild(clone);
            });
        })
        .catch(error => {
            console.error('Error loading recommendations:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading recommendations. Please try again later.</div>';
        });
}

function loadCatalog(search = '') {
    const container = document.getElementById('catalogList');
    if (!container) {
        return;
    }

    let url = '../api/catalog.php';
    if (search) {
        url += '?search=' + encodeURIComponent(search);
    }

    container.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading catalog...</span></div>';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                const warnCat = document.createElement('div');
                warnCat.className = 'alert alert-warning';
                warnCat.textContent = data.message || 'Could not load the catalog.';
                container.replaceChildren(warnCat);
                return;
            }

            const games = Array.isArray(data.data.games) ? data.data.games : [];
            if (games.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No games found.</div>';
                return;
            }

            const template = document.getElementById('catalogCardTemplate');
            container.innerHTML = '';
            games.forEach(game => {
                const clone = template.content.cloneNode(true);
                const img = clone.querySelector('.game-cover');
                if (game.portada_url) {
                    img.src = game.portada_url;
                    img.alt = game.titulo;
                } else {
                    img.remove();
                }
                clone.querySelector('.game-title').textContent = game.titulo;
                clone.querySelector('.game-description').textContent = game.descripcion ?? 'Sin descripción';
                container.appendChild(clone);
            });
        })
        .catch(error => {
            console.error('Error loading the catalog:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading the catalog. Please try again later.</div>';
        });
}

