<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/dashboard.css">

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

<button class="button">
    <div class="trash">
        <div class="top">
            <div class="paper"></div>
        </div>
        <div class="box"></div>
        <div class="check">
            <svg viewBox="0 0 8 6">
                <polyline points="1 3.4 2.71428571 5 7 1"></polyline>
            </svg>
        </div>
    </div>
    <span>Delete Item</span>
</button>

<!-- twitter -->
<a class="twitter" target="_top" href="https://twitter.com/aaroniker_me"><svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 72 72"><path d="M67.812 16.141a26.246 26.246 0 0 1-7.519 2.06 13.134 13.134 0 0 0 5.756-7.244 26.127 26.127 0 0 1-8.313 3.176A13.075 13.075 0 0 0 48.182 10c-7.229 0-13.092 5.861-13.092 13.093 0 1.026.118 2.021.338 2.981-10.885-.548-20.528-5.757-26.987-13.679a13.048 13.048 0 0 0-1.771 6.581c0 4.542 2.312 8.551 5.824 10.898a13.048 13.048 0 0 1-5.93-1.638c-.002.055-.002.11-.002.162 0 6.345 4.513 11.638 10.504 12.84a13.177 13.177 0 0 1-3.449.457c-.846 0-1.667-.078-2.465-.231 1.667 5.2 6.499 8.986 12.23 9.09a26.276 26.276 0 0 1-16.26 5.606A26.21 26.21 0 0 1 4 55.976a37.036 37.036 0 0 0 20.067 5.882c24.083 0 37.251-19.949 37.251-37.249 0-.566-.014-1.134-.039-1.694a26.597 26.597 0 0 0 6.533-6.774z"></path></svg></a>






    <main class="container py-4">
        <h1 class="mb-4">My reviews</h1>
        <div id="reviewsList" class="list-group"></div>
    </main>

    <script src="../js/main.js"></script>
    <script src="../dist/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/reviews.js"></script>
</body>
</html>
