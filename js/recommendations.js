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

function showRecommendationsStatus(type, text) {
    const spinner = document.getElementById('recommendationsSpinner');
    const message = document.getElementById('recommendationsMessage');
    if (!spinner || !message) {
        return;
    }

    if (type === 'loading') {
        spinner.classList.remove('d-none');
        message.classList.add('d-none');
        message.textContent = '';
        return;
    }

    spinner.classList.add('d-none');
    message.className = `alert alert-${type}`;
    message.textContent = text;
    message.classList.remove('d-none');
}

function hideRecommendationsStatus() {
    const spinner = document.getElementById('recommendationsSpinner');
    const message = document.getElementById('recommendationsMessage');
    if (!spinner || !message) {
        return;
    }

    spinner.classList.add('d-none');
    message.classList.add('d-none');
}

function loadRecommendations() {
    const container = document.getElementById('recommendationsList');
    if (!container) {
        return;
    }

    showRecommendationsStatus('loading');
    container.innerHTML = '';

    fetch('../api/recommendations.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showRecommendationsStatus('warning', data.message || 'Could not load recommendations.');
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                showRecommendationsStatus('info', 'No recommendations available.');
                return;
            }

            hideRecommendationsStatus();
            const template = document.getElementById('recommendationCardTemplate');
            container.innerHTML = '';
            data.data.forEach(game => {
                // Clonamos el template de la vista y luego rellenamos sus campos.
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
            showRecommendationsStatus('danger', 'Error loading recommendations. Please try again later.');
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

    showCatalogStatus('loading');
    container.innerHTML = '';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showCatalogStatus('warning', data.message || 'Could not load the catalog.');
                return;
            }

            const games = Array.isArray(data.data.games) ? data.data.games : [];
            if (games.length === 0) {
                showCatalogStatus('info', 'No games found.');
                return;
            }

            hideCatalogStatus();
            const template = document.getElementById('catalogCardTemplate');
            container.innerHTML = '';
            games.forEach(game => {
                // Clonamos el template de la vista y luego rellenamos sus campos.
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
            showCatalogStatus('danger', 'Error loading the catalog. Please try again later.');
        });
}

function showCatalogStatus(type, text) {
    const spinner = document.getElementById('catalogSpinner');
    const message = document.getElementById('catalogMessage');
    if (!spinner || !message) {
        return;
    }

    if (type === 'loading') {
        spinner.classList.remove('d-none');
        message.classList.add('d-none');
        message.textContent = '';
        return;
    }

    spinner.classList.add('d-none');
    message.className = `alert alert-${type}`;
    message.textContent = text;
    message.classList.remove('d-none');
}

function hideCatalogStatus() {
    const spinner = document.getElementById('catalogSpinner');
    const message = document.getElementById('catalogMessage');
    if (!spinner || !message) {
        return;
    }

    spinner.classList.add('d-none');
    message.classList.add('d-none');
}

