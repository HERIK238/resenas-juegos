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
function showDataStatus(type, text) {
    const spinner = document.getElementById('dataSpinner');
    const message = document.getElementById('dataMessage');
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

function hideDataStatus() {
    const spinner = document.getElementById('dataSpinner');
    const message = document.getElementById('dataMessage');
    if (!spinner || !message) {
        return;
    }

    spinner.classList.add('d-none');
    message.classList.add('d-none');
}

function loadUserData() {
    const table = $('#myTable');
    const tbody = table.find('tbody');
    const tbodyEl = tbody[0];
    if (!tbodyEl) {
        return;
    }

    const template = document.getElementById('userRowTemplate');
    if (!template) {
        console.error('User row template not found.');
        return;
    }

    showDataStatus('loading', 'Loading user data...');
    tbodyEl.innerHTML = '';

    fetch('../api/data_user.php')
        .then(res => res.json())
        .then(resp => {
            console.log('data_user response:', resp);

            const isSuccess = resp && (resp.status === 'success' || resp.success === true);
            const dataArray = Array.isArray(resp.data) ? resp.data : (Array.isArray(resp) ? resp : null);

            if (!isSuccess || !dataArray) {
                const msg = resp && (resp.message || resp.error || resp.msg || resp.message_error)
                    ? (resp.message || resp.error || resp.msg || resp.message_error)
                    : 'No se pudo obtener la información de usuarios.';
                showDataStatus('warning', msg);
                return;
            }

            const rows = dataArray.map(u => {
                // Clonamos una fila del template definido en el HTML y luego rellenamos sus datos.
                const clone = template.content.cloneNode(true);
                clone.querySelector('.user-id').textContent = u.id ?? '';
                clone.querySelector('.user-name').textContent = u.nombre ?? '';
                clone.querySelector('.user-email').textContent = u.email ?? '';
                clone.querySelector('.user-total').textContent = u.total_reseñas ?? u.total_resenas ?? 0;
                return clone;
            });

            tbodyEl.replaceChildren(...rows);
            hideDataStatus();

            if ($.fn.DataTable) {
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
            showDataStatus('danger', 'Error al cargar datos. Intenta más tarde.');
        });
}

// Ejecutar carga al iniciar (manteniendo compatibilidad con jQuery ready)
$(function () {
    loadUserData();
});