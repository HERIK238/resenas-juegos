document.addEventListener("DOMContentLoaded", () => {
    // Check if the user already has an active session on page load
    verificarSesion();

    // Attach the login form handler
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
// 1. LOGIN FUNCTION (Traditional)
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
            alert("Login successful! Redirecting...");
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
// 2. USER REGISTRATION + PREFERRED GENRES
// ==========================================
function ejecutarRegistro(formulario) {
    const formData = new FormData(formulario);
    
    // Read selected genres from the hidden bubble input
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
            alert("Registration completed successfully!");
            document.cookie = "intereses_completados=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "generos_juego=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.reload();
        } else {
            alert("Registration error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error in registration:", error);
        alert("There was a problem processing the registration.");
    });
}

// ==========================================
// 3. CHECK ACTIVE SESSION
// ==========================================
function verificarSesion() {
    fetch("../api/check_session.php")
    .then(response => response.json())
    .then(data => {
        const btnUserLogin = document.getElementById("btnUserLogin");
        const userProfile = document.getElementById("userProfile");
        const profilePic = document.getElementById("profilePic");
        
        if (data.logged) {
            console.log("User authenticated. Active session found.");
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
            console.log("Browsing as Guest.");
            if (btnUserLogin) btnUserLogin.style.display = "inline-flex";
            if (userProfile) userProfile.style.display = "none";
            evaluarMostrarBurbujas();
        }
    })
    .catch(error => console.error("Error verifying session:", error));
}

// ==========================================
// LOGOUT FUNCTION
// ==========================================
function ejecutarLogout() {
    fetch("../api/logout.php", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": getCsrfToken() }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success" || data.message === "Logout exitoso") {
            alert("You have logged out successfully.");
            window.location.reload();
        } else {
            alert("Logout error: " + (data.message || data.error));
        }
    })
    .catch(error => {
        console.error("Error en el logout:", error);
        alert("There was a problem logging out.");
    });
}

// ==========================================
// 4. GUEST GENRE BUBBLE LOGIC
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
        alert("Please select at least one genre.");
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
// BUBBLE SELECTION: TOGGLE STYLES ON CLICK
// ==========================================
document.addEventListener("click", function(e) {
    if (e.target.classList.contains("bubble")) {
        e.target.classList.toggle("selected");
        
        const seleccionadas = document.querySelectorAll(".bubble.selected");
        const valores = Array.from(seleccionadas).map(b => b.getAttribute("data-value"));
        
        const generosInput = document.getElementById("generos_input");
        if (generosInput) {
            generosInput.value = valores.join(",");
            console.log("Selected genres ready to save: ", generosInput.value); 
        }
    }
});

// ==========================================
// GLOBAL GOOGLE SIGN-IN RESPONSE
// (Does not use CSRF — Google verifies the id_token directly)
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
            // No X-CSRF-TOKEN — the id_token from Google is the verification
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
            
            alert("Welcome! You have successfully signed in.");
        } else {
            alert("Google Login error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error enviando token a Google Login:", error);
        alert("Hubo un problema al procesar el login con Google.");
    });
};