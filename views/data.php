<?php
session_start();
require_once __DIR__ . '/../api/config/env.php';
EnvLoader::load(__DIR__ . '/../api/config/.env');
$google_client_id = EnvLoader::get('GOOGLE_CLIENT_ID', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link real="stylesheet" href="../dist/datatables/css/dataTables.bootstrap5.min.css">
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
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#" id="homeLink" onclick="window.openUserHome()">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="reviewsLink" onclick="window.openUserReviews()">reviews</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="recommendationsLink" onclick="window.openUserRecommendations()">recommendations</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="dataLink" onclick="window.openUserData()">data</a></li>
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

    <div class="custom-modal auth-modal" id="ModalLogin">
        <div class="modal-dialog auth-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between border-0">
                    <h5 class="fw-bold mb-0">Sign in</h5>
                    <button type="button" class="btn-close" onclick="closeLogin()"></button>
                </div>
                <div class="modal-body">
                    <div id="g_id_onload"
                         data-client_id="<?= htmlspecialchars($google_client_id) ?>"
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

<table id="myTable" class="display">
    <thead>
        <tr>
            <th>id</th>
            <th>username</th>
            <th>email</th>
            <th>reseñas</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
            <td>Row 1 Data 3</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
            <td>Row 2 Data 3</td>
            <td>0</td>
        </tr>
        <tr>
            <td>Row 3 Data 1</td>
            <td>Row 3 Data 2</td>
            <td>Row 3 Data 3</td>
            <td>0</td>
        </tr>
    </tbody>
</table>

    <script src="../dist/jquery/js/jquery.min.js"></script>
    <script src="../dist/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/dashboard.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/data.js"></script>
</body>
</html>