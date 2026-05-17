document.addEventListener("DOMContentLoaded", () => {
    // Verificar si el usuario ya tiene sesión iniciada al cargar la página
    verificarSesion();

    // Conectar el formulario de Inicio de Sesión
    const formLogin = document.getElementById("formLogin");
    if (formLogin) {
        formLogin.addEventListener("submit", (e) => {
            e.preventDefault();
            ejecutarLogin(formLogin);
        });
    }

    // Conectar el formulario de Registro (Si usas uno independiente o el mismo modal)
    const formRegistro = document.getElementById("formRegistro");
    if (formRegistro) {
        formRegistro.addEventListener("submit", (e) => {
            e.preventDefault();
            ejecutarRegistro(formRegistro);
        });
    }
});

// ==========================================
// 1. FUNCIÓN PARA INICIAR SESIÓN (Tradicional)
// ==========================================
function ejecutarLogin(formulario) {
    const formData = new FormData(formulario);

    fetch("../api/auth_user.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("¡Login exitoso! Redireccionando...");
            window.location.reload(); // Recarga la página para actualizar el estado
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error en el login:", error);
        alert("Hubo un problema al conectar con el servidor.");
    });
}

// ==========================================
// 2. FUNCIÓN PARA REGISTRAR USUARIO + GÉNEROS
// ==========================================
function ejecutarRegistro(formulario) {
    const formData = new FormData(formulario);
    
    // Obtenemos los géneros seleccionados en las burbujas desde el input oculto
    const generosInput = document.getElementById("generos_input");
    if (generosInput && generosInput.value) {
        formData.append("generos_juego", generosInput.value);
    }

    fetch("../api/reg_user.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("¡Registro completado con éxito!");
            // Si venía de una cookie de invitado, la limpiamos en JS también
            document.cookie = "intereses_completados=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "generos_juego=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.reload();
        } else {
            alert("Error al registrarse: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error en el registro:", error);
        alert("Hubo un problema al procesar el registro.");
    });
}

// ==========================================
// 3. FUNCIÓN PARA VERIFICAR LA SESIÓN ACTIVA
// ==========================================
function verificarSesion() {
    fetch("../api/check_session.php")
    .then(response => response.json())
    .then(data => {
        if (data.logged) {
            console.log("Usuario autenticado. ID de sesión activo.");
            // Aquí puedes ocultar los botones de "Iniciar Sesión" y mostrar el perfil
            const btnLoginNavbar = document.querySelector(".btn-login");
            if (btnLoginNavbar) btnLoginNavbar.style.display = "none";
        } else {
            console.log("Navegando como Invitado.");
            // Si es invitado, evaluamos si mostrar la pantalla de burbujas Spotify
            evaluarMostrarBurbujas();
        }
    })
    .catch(error => console.error("Error verificando sesión:", error));
}

// ==========================================
// 4. LÓGICA DE LAS BURBUJAS PARA INVITADOS
// ==========================================
function evaluarMostrarBurbujas() {
    const yaRespondio = getCookie("intereses_completados");
    if (yaRespondio !== "true") {
        const modalBurbujas = document.getElementById("ModalBurbujas"); // Asegúrate de que el ID coincida
        if (modalBurbujas) {
            modalBurbujas.classList.add("show");
            modalBurbujas.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
    }
}

// Guardar selección temporal si el invitado da clic en "Hecho" antes de registrarse
function finalizarSeleccionInvitado() {
    const generos = document.getElementById("generos_input").value;
    if (!generos) {
        alert("Por favor, selecciona al menos un género.");
        return;
    }
    
    // Guardamos las cookies por 30 días
    setCookie("intereses_completados", "true", 30);
    setCookie("generos_juego", generos, 30);
    
    // Cerrar el modal de pantalla completa
    const modalBurbujas = document.getElementById("ModalBurbujas");
    if (modalBurbujas) {
        modalBurbujas.classList.remove("show");
        modalBurbujas.style.display = "none";
        document.body.style.overflow = "";
    }
}

// Funciones auxiliares para manejo de cookies
function setCookie(nombre, valor, dias) {
    let fecha = new Date();
    fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
    document.cookie = nombre + "=" + valor + ";expires=" + fecha.toUTCString() + ";path=/";
}

function getCookie(nombre) {
    let name = nombre + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

// ==========================================
// NUEVO: QUE LAS BURBUJAS CAMBIEN DE COLOR AL DAR CLIC
// ==========================================
document.addEventListener("click", function(e) {
    // Verifica si el elemento al que le diste clic tiene la clase 'bubble'
    if (e.target.classList.contains("bubble")) {
        
        // 1. Pone o quita el color rojo (la clase 'selected' del CSS)
        e.target.classList.toggle("selected");
        
        // 2. Busca todas las burbujas que están rojas en este momento
        const seleccionadas = document.querySelectorAll(".bubble.selected");
        
        // 3. Extrae sus nombres (Acción, RPG, etc.)
        const valores = Array.from(seleccionadas).map(b => b.getAttribute("data-value"));
        
        // 4. Los guarda en el input oculto
        const generosInput = document.getElementById("generos_input");
        if (generosInput) {
            generosInput.value = valores.join(",");
            // Mostramos en consola para verificar que funciona
            console.log("Géneros elegidos listos para guardar: ", generosInput.value); 
        }
    }
});

// ==========================================
// RESPUESTA GLOBAL DE GOOGLE SIGN-IN
// ==========================================
window.handleCredentialResponse = function(response) {
    console.log("Token de Google recibido de forma segura: ", response.credential);
    
    // Como tu función closeLogin() está en el otro archivo JS, 
    // verificamos que exista antes de llamarla para evitar errores.
    if (typeof closeLogin === "function") {
        closeLogin();
    }
    
    alert("¡Google te ha validado correctamente! Revisa la consola.");
    
    // Aquí irá luego el fetch() para conectar con tu PHP
};