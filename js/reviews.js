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


window.openUserConfig = function () {
    try {
        window.location.href = "../views/settings.php";
    }catch (error) {
        console.log("error al abrir la configuracion del usuario:", error)
    }
}

window.openUserData = function () {
    try {
        window.location.href = "../views/data.php";
    } catch (error) {
        console.error("Error al abrir datos del usuario:", error);
    }
}

function showReviewStatus(type, text) {
    const spinner = document.getElementById('reviewsSpinner');
    const message = document.getElementById('reviewsMessage');
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

function hideReviewStatus() {
    const spinner = document.getElementById('reviewsSpinner');
    const message = document.getElementById('reviewsMessage');
    if (!spinner || !message) {
        return;
    }

    spinner.classList.add('d-none');
    message.classList.add('d-none');
}

function loadUserReviews() {
    const container = document.getElementById('reviewsList');
    if (!container) {
        return;
    }

    showReviewStatus('loading');
    container.innerHTML = '';

    fetch('../api/reviews.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showReviewStatus('warning', data.message || 'Could not load reviews.');
                return;
            }

            if (!Array.isArray(data.data) || data.data.length === 0) {
                showReviewStatus('info', 'No reviews found.');
                return;
            }

            hideReviewStatus();
            const template = document.getElementById('reviewTemplate');
            container.innerHTML = '';
            data.data.forEach(review => {
                // Aquí no generamos HTML desde cadenas.
                // Clonamos el template que ya está definido en views/reviews.php y luego rellenamos los valores.
                const clone = template.content.cloneNode(true);
                const item = clone.querySelector('.review-item');

                item.id = `review-${review.id}`;
                clone.querySelector('.review-title').textContent = review.review_title;
                clone.querySelector('.review-body').textContent = review.review_content;
                clone.querySelector('.review-game').textContent = `Game: ${review.game_title || 'Unknown'}`;
                clone.querySelector('.review-date').textContent = `Creada el ${new Date(review.created_at).toLocaleString()}`;
                clone.querySelector('.review-delete-btn').addEventListener('click', () => deleteReview(review.id));

                container.appendChild(clone);
            });
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            showReviewStatus('danger', 'Error loading reviews. Please try again later.');
        });
}

function deleteReview(reviewId) {
    Swal.fire({
        title: '¿Eliminar reseña?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('../api/delete_review.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            },
            body: JSON.stringify({ review_id: reviewId })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`review-${reviewId}`)?.remove();
                    const container = document.getElementById('reviewsList');
                    if (container && !container.querySelector('.review-item')) {
                        showReviewStatus('info', 'No reviews found.');
                    }
                    Swal.fire('Eliminada', 'La reseña fue eliminada.', 'success');
                } else {
                    Swal.fire('Error', data.message || 'No se pudo eliminar.', 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
    });
}