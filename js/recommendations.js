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

window.openUserReviews = function () {
    try {
        window.location.href = "../views/reviews.php";
    } catch (error) {
        console.error("Error opening user reviews:", error);
    }

    try {
        window.location.href = "../views/dashboard.php";
    } catch (error) {
        console.error("Error opening user dashboard:", error);
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
                container.innerHTML = `<div class="alert alert-warning">${data.message || 'Could not load recommendations.'}</div>`;
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No recommendations available.</div>';
                return;
            }

            container.innerHTML = data.data.map(game => {
                return `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            ${game.portada_url ? `<img src="${escapeHtml(game.portada_url)}" class="card-img-top" alt="${escapeHtml(game.titulo)}">` : ''}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${escapeHtml(game.titulo)}</h5>
                                <p class="card-text text-truncate">${escapeHtml(game.descripcion ?? 'Sin descripción')}</p>
                                <div class="mt-auto">
                                    <small class="text-muted">Creado el ${new Date(game.created_at).toLocaleDateString()}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
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
                container.innerHTML = `<div class="alert alert-warning">${data.message || 'Could not load the catalog.'}</div>`;
                return;
            }

            const games = Array.isArray(data.data.games) ? data.data.games : [];
            if (games.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No games found.</div>';
                return;
            }

            container.innerHTML = games.map(game => {
                return `
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100">
                            ${game.portada_url ? `<img src="${escapeHtml(game.portada_url)}" class="card-img-top" alt="${escapeHtml(game.titulo)}">` : ''}
                            <div class="card-body">
                                <h5 class="card-title">${escapeHtml(game.titulo)}</h5>
                                <p class="card-text text-truncate">${escapeHtml(game.descripcion ?? 'Sin descripción')}</p>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error loading the catalog:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading the catalog. Please try again later.</div>';
        });
}

function escapeHtml(value) {
    if (typeof value !== 'string') {
        return value;
    }
    return value
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}