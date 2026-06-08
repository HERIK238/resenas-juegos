document.addEventListener('DOMContentLoaded', () => {
    loadUserReviews();
});

window.openUserHome = function () {
    try {
        window.location.href = "../views/dashboard.php";
    } catch (error) {
        console.error("Error al abrir home del usuario:", error);
    }
}

window.openUserRecommendations = function () {
    try {
        window.location.href = "../views/recommendations.php";
    } catch (error) {
        console.error("Error al abrir recomendaciones del usuario:", error);
    }
}

function loadUserReviews() {
    const container = document.getElementById('reviewsList');
    if (!container) {
        return;
    }

    container.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

    fetch('../api/reviews.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                container.innerHTML = `<div class="alert alert-warning">${data.message || 'Could not load reviews.'}</div>`;
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No reviews found.</div>';
                return;
            }

            container.innerHTML = data.data.map(review => {
                return `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">${escapeHtml(review.review_title)}</h5>
                                <p class="mb-1">${escapeHtml(review.review_content)}</p>
                                <small class="text-muted">Game: ${escapeHtml(review.game_title || 'Unknown')}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">${review.calificacion ?? 'N/A'}</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Creada el ${new Date(review.created_at).toLocaleString()}</small>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading reviews. Please try again later.</div>';
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