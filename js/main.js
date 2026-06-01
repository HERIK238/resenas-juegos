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
// UTILIDAD: LEER EL TOKEN CSRF DEL <meta>
// ==========================================
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

// ==========================================
// 1. FUNCIÓN PARA INICIAR SESIÓN (Tradicional)
// ==========================================
function ejecutarLogin(formulario) {
    const formData = new FormData(formulario);

    fetch("../api/auth_user.php", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": getCsrfToken() },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("¡Login exitoso! Redireccionando...");
            window.location.reload();
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
        headers: { "X-CSRF-TOKEN": getCsrfToken() },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("¡Registro completado con éxito!");
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
        const btnUserLogin = document.getElementById("btnUserLogin");
        const userProfile = document.getElementById("userProfile");
        const profilePic = document.getElementById("profilePic");
        
        if (data.logged) {
            console.log("Usuario autenticado. ID de sesión activo.");
            if (btnUserLogin) btnUserLogin.style.display = "none";
            if (userProfile) {
                userProfile.style.display = "flex";

            const dropdownUsername = document.getElementById("dropdownUsername");
            if (dropdownUsername) dropdownUsername.textContent = data.username;
                if (data.profile_picture && profilePic) {
                    profilePic.src = data.profile_picture;
                    profilePic.style.display = "block";
                } else if (profilePic) {
                    profilePic.style.display = "none";
                }
            }
        } else {
            console.log("Navegando como Invitado.");
            if (btnUserLogin) btnUserLogin.style.display = "inline-flex";
            if (userProfile) userProfile.style.display = "none";
            evaluarMostrarBurbujas();
        }
    })
    .catch(error => console.error("Error verificando sesión:", error));
}

// ==========================================
// FUNCIÓN PARA CERRAR SESIÓN
// ==========================================
function ejecutarLogout() {
    fetch("../api/logout.php", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": getCsrfToken() }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success" || data.message === "Logout exitoso") {
            alert("Has cerrado sesión correctamente.");
            window.location.reload();
        } else {
            alert("Error al cerrar sesión: " + (data.message || data.error));
        }
    })
    .catch(error => {
        console.error("Error en el logout:", error);
        alert("Hubo un problema al cerrar sesión.");
    });
}

// ==========================================
// 4. LÓGICA DE LAS BURBUJAS PARA INVITADOS
// ==========================================
function evaluarMostrarBurbujas() {
    const yaRespondio = getCookie("intereses_completados");
    if (yaRespondio !== "true") {
        const modalBurbujas = document.getElementById("ModalBurbujas");
        if (modalBurbujas) {
            modalBurbujas.classList.add("show");
            modalBurbujas.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
    }
}

function finalizarSeleccionInvitado() {
    const generos = document.getElementById("generos_input").value;
    if (!generos) {
        alert("Por favor, selecciona al menos un género.");
        return;
    }
    
    setCookie("intereses_completados", "true", 30);
    setCookie("generos_juego", generos, 30);
    
    const modalBurbujas = document.getElementById("ModalBurbujas");
    if (modalBurbujas) {
        modalBurbujas.classList.remove("show");
        modalBurbujas.style.display = "none";
        document.body.style.overflow = "";
    }
}

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
// BURBUJAS: CAMBIAR COLOR AL DAR CLIC
// ==========================================
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("bubble")) {
        e.target.classList.toggle("selected");
        
        const seleccionadas = document.querySelectorAll(".bubble.selected");
        const valores = Array.from(seleccionadas).map(b => b.getAttribute("data-value"));
        
        const generosInput = document.getElementById("generos_input");
        if (generosInput) {
            generosInput.value = valores.join(",");
            console.log("Géneros elegidos listos para guardar: ", generosInput.value); 
        }
    }
});

// ==========================================
// RESPUESTA GLOBAL DE GOOGLE SIGN-IN
// (No usa CSRF — Google verifica el id_token directamente)
// ==========================================
window.handleCredentialResponse = function(response) {
    console.log("Token de Google recibido de forma segura");
    
    if (typeof closeLogin === "function") {
        closeLogin();
    }
    
    fetch("../api/google_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
            // Sin X-CSRF-TOKEN — el id_token de Google es la verificación
        },
        body: JSON.stringify({ token: response.credential })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const btnUserLogin = document.getElementById("btnUserLogin");
            const userProfile = document.getElementById("userProfile");
            const profilePic = document.getElementById("profilePic");
            
            if (btnUserLogin) btnUserLogin.style.display = "none";
            if (userProfile) {
                userProfile.style.display = "flex";
                if (data.profile_picture && profilePic) {
                    profilePic.src = data.profile_picture;
                }
            }
            
            alert("¡Bienvenido! Sesión iniciada correctamente.");
        } else {
            alert("Error en Google Login: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error enviando token a Google Login:", error);
        alert("Hubo un problema al procesar el login con Google.");
    });
};