<?php require_once __DIR__ . '/../api/config/env.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <title>Reviews</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../dist/sweetalert2/css/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/reviews.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">

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
            <li class="nav-item"><a class="nav-link" href="#" id="reviewsLink">reviews</a></li>
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


    <main class="container py-4">
        <h1 class="mb-4">My reviews</h1>
        <div id="reviewsList" class="list-group"></div>
    </main>

    <template id="reviewTemplate">
        <div class="list-group-item review-item">
            <div class="d-flex justify-content-between align-items-start">
                <div class="review-info">
                    <h5 class="mb-1 review-title"></h5>
                    <p class="mb-1 review-body"></p>
                    <small class="text-muted review-game"></small>
                </div>
                <button class="btn btn-delete review-delete-btn" title="Eliminar reseña">
                    <img src="../assets/imagenes/basura.png" alt="Delete">
                </button>
            </div>
            <div class="mt-2">
                <small class="text-muted review-date"></small>
            </div>
        </div>
    </template>

    <script src="../js/main.js"></script>
    <script src="../dist/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/sweetalert2/js/sweetalert2.all.min.js"></script>
    <script src="../js/reviews.js"></script>
</body>
</html>
