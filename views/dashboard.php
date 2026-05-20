# Video Game 🎮 Reviews
// 1. Cargamos el archivo de configuración para obtener el ID de Google de tu .env
// Ajusta la ruta a tu config.php dependiendo de dónde esté este archivo
require_once __DIR__ . '/../api/config/env.php'; 
EnvLoader::load(__DIR__ . '/../api/config/.env');
$google_client_id = EnvLoader::get('GOOGLE_CLIENT_ID', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reviews Games - Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
          </ul>
          
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>

          <!-- Botón de Login circular -->
          <button class="btn btn-user-login ms-3" id="btnUserLogin" onclick="openLogin()" title="Iniciar sesión">
              <i class="bi bi-person-circle"></i>
          </button>
          
          <!-- Foto de perfil del usuario (aparece cuando está autenticado) -->
          <div class="user-profile ms-3" id="userProfile" style="display: none;">
              <img id="profilePic" src="" alt="Perfil" class="rounded-circle" width="40" height="40">
              <button class="btn btn-logout ms-2 btn-sm" onclick="ejecutarLogout()" title="Cerrar sesión">
                  <i class="bi bi-box-arrow-right"></i>
              </button>
          </div>
        </div>
      </div>
    </nav>

    <button type="button" id="btn_pri" class="btn btn-pri">+</button>

    <div class="custom-modal" id="Modal" style="display:none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Reviews</h5>
            <button type="button" class="btn-close btn-cerrar" onclick="document.getElementById('Modal').style.display='none'"></button>
          </div>
          <div class="modal-body">
            <input type="text" class="form-control mb-3" placeholder="Review title"/>
            <textarea class="form-control mb-3" placeholder="Review content"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cerrar" onclick="document.getElementById('Modal').style.display='none'">Close</button>
            <button type="button" class="btn btn-primary">Upload review</button>
          </div>
        </div>
      </div>
    </div>

    <div class="custom-modal" id="ModalLogin" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-dialog" style="background: white; border-radius: 10px; padding: 20px; width: 100%; max-width: 400px;">
            <div class="modal-content" style="border: none;">
                <div class="modal-header d-flex justify-content-between border-0">
                    <h5 class="fw-bold mb-0">Inicia sesión</h5>
                    <button type="button" class="btn-close" onclick="closeLogin()"></button>
                </div>
                <div class="modal-body">
                    
                    <div id="g_id_onload"
                         data-client_id="<?php echo $google_client_id; ?>"
                         data-context="signin"
                         data-ux_mode="popup"
                         data-callback="handleCredentialResponse"
                         data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin d-flex justify-content-center mb-4 w-100"
                         data-type="standard"
                         data-shape="rectangular"
                         data-theme="outline"
                         data-text="signin_with"
                         data-size="large"
                         data-logo_alignment="left"
                         data-width="360">
                    </div>

                    <div class="text-center text-muted mb-3" style="font-size: 0.85rem;">o con tu correo</div>

                    <form id="formLogin">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Usuario o Correo</label>
                            <input type="text" name="username" class="form-control" placeholder="ejemplo@correo.com" autocomplete="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" autocomplete="current-password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn w-100 py-2 fw-bold text-white" style="background-color: #072B73;">Entrar</button>
                    </form>

                    <div class="text-center mt-4 small text-muted">
                        ¿No tienes cuenta? <a href="#" onclick="closeLogin(); openRegistro();" class="text-danger fw-bold text-decoration-none">Regístrate aquí</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="ModalRegistro" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-dialog" style="background: white; border-radius: 10px; padding: 20px; width: 100%; max-width: 400px;">
            <div class="modal-content" style="border: none;">
                <div class="modal-header d-flex justify-content-between border-0">
                    <h5 class="fw-bold mb-0">Crea tu cuenta</h5>
                    <button type="button" class="btn-close" onclick="closeRegistro()"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistro">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre de Usuario</label>
                            <input type="text" name="username" class="form-control" autocomplete="username" placeholder="Tu nick de jugador" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" autocomplete="email" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn w-100 py-2 fw-bold text-white" style="background-color: #072B73;">Registrarse</button>
                    </form>
                    
                    <div class="text-center mt-3 small">
                        ¿Ya tienes cuenta? <a href="#" onclick="closeRegistro(); openLogin();" class="text-danger fw-bold text-decoration-none">Inicia Sesión aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="ModalBurbujas" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: #121212; z-index: 99999; flex-direction: column; justify-content: center; align-items: center;">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-white" style="font-size: 3rem;">¿Qué te gusta jugar?</h1>
            <p class="text-white-50">Selecciona tus géneros favoritos para personalizar tus recomendaciones.</p>
        </div>

        <div class="interests-grid" style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; max-width: 800px; padding: 20px;">
            <div class="bubble" data-value="Acción">Acción</div>
            <div class="bubble" data-value="Aventura">Aventura</div>
            <div class="bubble" data-value="RPG">RPG</div>
            <div class="bubble" data-value="Shooter">Shooter</div>
            <div class="bubble" data-value="Supervivencia">Supervivencia</div>
            <div class="bubble" data-value="Terror">Terror</div>
            <div class="bubble" data-value="Estrategia">Estrategia</div>
            <div class="bubble" data-value="Deportes">Deportes</div>
            <div class="bubble" data-value="Mundo Abierto">Mundo Abierto</div>
            <div class="bubble" data-value="Simulación">Simulación</div>
        </div>

        <div class="mt-5">
            <button type="button" class="btn btn-light btn-lg px-5 fw-bold" style="border-radius: 50px;" onclick="finalizarSeleccionInvitado()">
                Hecho
            </button>
        </div>

        <input type="hidden" id="generos_input" name="generos_juego">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="../js/dashboard.js"></script>
    <script defer src="../js/main.js"></script>
</body>
</html>