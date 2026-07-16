<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recommendations</title>

    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/dashboard.css">
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
            <li class="nav-item"><a class="nav-link" href="#" id="reviewsLink" onclick="window.openUserReviews()">reviews</a></li>
            <li class="nav-item"><a class="nav-link" href="#">recommendations</a></li>
            <?php
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (isset($_SESSION['role_id']) && (int)$_SESSION['role_id'] === 1):
            ?>
                <li class="nav-item"><a class="nav-link" href="#" id="dataLink" onclick="window.openUserData()">data</a></li>
            <?php endif; ?>
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
        <h1 class="mb-4">Recommendations</h1>
        <div id="recommendationsList" class="row gy-4"></div>

        <section class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Catalog</h2>
                <div class="w-50">
                    <input id="catalogSearch" type="search" class="form-control" placeholder="Search games or genre" />
                </div>
            </div>
            <div id="catalogList" class="row gy-4"></div>
        </section>
    </main>

    <template id="recommendationCardTemplate">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <img class="card-img-top game-cover" alt="">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title game-title"></h5>
                    <p class="card-text text-truncate game-description"></p>
                    <div class="mt-auto">
                        <small class="text-muted game-date"></small>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="catalogCardTemplate">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <img class="card-img-top game-cover" alt="">
                <div class="card-body">
                    <h5 class="card-title game-title"></h5>
                    <p class="card-text text-truncate game-description"></p>
                </div>
            </div>
        </div>
    </template>

    <script src="../js/main.js"></script>
    <script src="../dist/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/recommendations.js"></script>
</body>
</html>