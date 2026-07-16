// open user settings

window.openUserConfig = function () {
    try {
        window.location.href = "../views/settings.php";
    } catch (error) {
        console.error("Error opening user settings:", error);
    }
}

//open user dashboard
window.openUserHome = function () {
    try {
        window.location.href = "../views/dashboard.php";
    } catch (error) {
        console.error("Error al abrir home del usuario:", error);
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

// Carga usuarios desde la API y luego inicializa DataTables
function loadUserData() {
    const table = $('#myTable');
    const tbody = table.find('tbody');

    fetch('../api/data_user.php')
        .then(res => res.json())
        .then(resp => {
            console.log('data_user response:', resp);

            // Manejar varias formas de respuesta de la API
            const isSuccess = resp && (resp.status === 'success' || resp.success === true);
            const dataArray = Array.isArray(resp.data) ? resp.data : (Array.isArray(resp) ? resp : null);

            if (!isSuccess || !dataArray) {
                const msg = resp && (resp.message || resp.error || resp.msg || resp.message_error) ? (resp.message || resp.error || resp.msg || resp.message_error) : 'No se pudo obtener la información de usuarios.';
                tbody.html(`<tr><td colspan="3">${msg}</td></tr>`);
                return;
            }

            // Construir filas
            const rows = resp.data.map(u => {
                const id = u.id ?? '';
                const username = u.nombre ?? '';
                const email = u.email ?? '';
                const total = u.total_reseñas ?? u.total_resenas ?? 0;
                return `<tr><td>${id}</td><td>${username}</td><td>${email}</td><td>${total}</td></tr>`;
            }).join('');

            tbody.html(rows);

            // Inicializar DataTable después de poblar
            if ($.fn.DataTable) {
                // Si ya estaba inicializada, destruirla primero
                if ($.fn.dataTable.isDataTable(table)) {
                    table.DataTable().clear().destroy();
                }

                table.DataTable({
                    responsive: true,
                    ordering: true,
                    searching: true,
                    pageLength: 10,
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ filas',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        }
                    }
                });
            } else {
                console.error('DataTables jQuery no está cargado. Verifica que jQuery y jquery.dataTables.min.js se carguen correctamente.');
            }
        })
        .catch(err => {
            console.error('Error cargando usuarios:', err);
            tbody.html('<tr><td colspan="3">Error al cargar datos. Intenta más tarde.</td></tr>');
        });
}

// Ejecutar carga al iniciar (manteniendo compatibilidad con jQuery ready)
$(function () {
    loadUserData();
});