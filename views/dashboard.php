<!-- 1. Load the config file to get the Google client ID from .env -->
<!-- Adjust the path to config.php depending on where this file is located -->
<?php
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
    <link rel="icon" type="image/png" href="../assets/logo.png">
    
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <!-- <a class="navbar-brand" href="#">Navbar</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="reviewsLink" onclick="window.openUserReviews()">reviews</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="recommendationsLink" onclick="window.openUserRecommendations()">recommendations</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                catalog
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Adventure</a></li>
                <li><a class="dropdown-item" href="#">Sports</a></li>
                <li><a class="dropdown-item" href="#">Strategy</a></li>
                <li><a class="dropdown-item" href="#">Open World</a></li>
                <li><a class="dropdown-item" href="#">RPG</a></li>
                <li><a class="dropdown-item" href="#">Shooter</a></li>
                <li><a class="dropdown-item" href="#">Simulation</a></li>
                <li><a class="dropdown-item" href="#">Survival</a></li>
                <li><a class="dropdown-item" href="#">Horror</a></li>
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

          <!-- Circular login button -->
          <button class="btn btn-user-login ms-3" id="btnUserLogin" onclick="openLogin()" title="Login">
              <i class="bi bi-person-circle"></i>
          </button>
          
          <!-- User profile photo (shown when authenticated) -->
<!-- User profile photo (shown when authenticated) -->
<div class="user-profile ms-3 dropdown hidden" id="userProfile">
    <img 
        id="profilePic" 
        src="" 
        alt="Profile" 
        class="rounded-circle dropdown-toggle" 
        width="40" 
        height="40"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <span class="dropdown-item-text fw-bold" id="dropdownUsername"></span>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <button class="dropdown-item text-config" id="dropdownConfig" onclick="window.openUserConfig()">
                <i class="bi bi-gear me-2"></i>Settings
            </button>
        </li>
        <li>
            <button class="dropdown-item text-danger" onclick="ejecutarLogout()">
                <i class="bi bi-box-arrow-right me-2"></i>Log out
            </button>
        </li>
    </ul>
</div>
        </div>
      </div>
    </nav>

    <button type="button" id="btn_pri" class="btn btn-pri">+</button>

    <div class="custom-modal" id="Modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">New Review</h5>
            <button type="button" class="btn-close btn-cerrar" onclick="document.getElementById('Modal').style.display='none'"></button>
          </div>
          <div class="modal-body">
            <input id="reviewGameName" type="text" class="form-control mb-3" placeholder="Game name"/>
            <input id="reviewTitle" type="text" class="form-control mb-3" placeholder="Review title"/>
            <textarea id="reviewContent" class="form-control mb-3" placeholder="Write your review or game guide"></textarea>
            <select id="reviewGenre" class="form-select mb-3">
              <option selected disabled value="">Select game genre</option>
            </select>
            <!-- <input id="reviewRating" type="number" min="0" max="10" step="0.5" class="form-control" placeholder="Rating (0-10)" /> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cerrar" onclick="document.getElementById('Modal').style.display='none'">Close</button>
            <button id="btnUploadReview" type="button" class="btn btn-primary">Upload review</button>
          </div>
        </div>
      </div>
    </div>

    <div class="custom-modal auth-modal" id="ModalLogin">
        <div class="modal-dialog auth-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between border-0">
                    <h5 class="fw-bold mb-0">Sign in</h5>
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

                    <div class="text-center text-muted mb-3 login-help-text">or with your email</div>

                    <form id="formLogin">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username or Email</label>
                            <input type="text" name="username" class="form-control" placeholder="example@email.com" autocomplete="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" autocomplete="current-password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn w-100 py-2 fw-bold text-white btn-auth">Sign in</button>
                    </form>

                    <div class="text-center mt-4 small text-muted">
                        Don't have an account? <a href="#" onclick="closeLogin(); openRegistro();" class="text-danger fw-bold text-decoration-none">Register here</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal auth-modal" id="ModalRegistro">
        <div class="modal-dialog auth-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between border-0">
                    <h5 class="fw-bold mb-0">Create account</h5>
                    <button type="button" class="btn-close" onclick="closeRegistro()"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistro">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" autocomplete="username" placeholder="Your gamer tag" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email address</label>
                            <input type="email" name="email" class="form-control" autocomplete="email" placeholder="example@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn w-100 py-2 fw-bold text-white btn-auth">Register</button>
                    </form>
                    
                    <div class="text-center mt-3 small">
                        Already have an account? <a href="#" onclick="closeRegistro(); openLogin();" class="text-danger fw-bold text-decoration-none">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal bubble-modal" id="ModalBurbujas">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-white bubble-modal-title">What do you like to play?</h1>
            <p class="text-white-50">Select your favorite genres to personalize recommendations.</p>
        </div>

        <div class="interests-grid">
            <div class="bubble" data-="Action">Action</div>
            <div class="bubble" data-="Adventure">Adventure</div>
            <div class="bubble" data-="RPG">RPG</div>
            <div class="bubble" data-="Shooter">Shooter</div>
            <div class="bubble" data-="Survival">Survival</div>
            <div class="bubble" data-="Horror">Horror</div>
            <div class="bubble" data-="Strategy">Strategy</div>
            <div class="bubble" data-="Sports">Sports</div>
            <div class="bubble" data-="Open World">Open World</div>
            <div class="bubble" data-="Simulation">Simulation</div>
        </div>

        <div class="mt-5">
            <button type="button" class="btn btn-light btn-lg px-5 fw-bold btn-pill" onclick="finalizarSeleccionInvitado()">
                Done
            </button>
        </div>

        <input type="hidden" id="generos_input" name="generos_juego">
    </div>

<div class="container mt-4">
    <div id="reseñasContaines"></div>
</div>

<template id="reseñaCardTemplate">
    <div class="reseña-card mb-3">
        <div class="reseña-card-header reseña-username"></div>
        <div class="reseña-card-body">
            <h5 class="reseña-card-title reseña-title"></h5>
            <p class="reseña-card-text reseña-content"></p>
        </div>
        <div class="reseña-card-footer reseña-date"></div>
    </div>
</template>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script  src="../js/dashboard.js"></script>
    <script defer src="../js/main.js"></script>
</body>
</html>