<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/settings.css">
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
            <li class="nav-item"><a class="nav-link" href="#">Reviews</a></li>
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

    <main class="container my-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h1 class="card-title mb-4">Account Settings</h1>

              <form class="settings-form" method="post" action="#">
                <section class="mb-4">
                  <h2 class="h5 mb-3">Profile</h2>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Your gamer name">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com">
                  </div>
                  <div class="mb-3">
                    <label for="favorite-genre" class="form-label">Favorite genre</label>
                    <select id="favorite-genre" name="favorite_genre" class="form-select">
                      <option value="">Select a genre</option>
                      <option value="action">Action</option>
                      <option value="adventure">Adventure</option>
                      <option value="sports">Sports</option>
                      <option value="strategy">Strategy</option>
                      <option value="open-world">Open World</option>
                      <option value="rpg">RPG</option>
                      <option value="shooter">Shooter</option>
                      <option value="simulation">Simulation</option>
                      <option value="survival">Survival</option>
                      <option value="horror">Horror</option>
                    </select>
                  </div>
                </section>

                <section class="mb-4">
                  <h2 class="h5 mb-3">Security</h2>
                  <div class="mb-3">
                    <label for="current-password" class="form-label">Current password</label>
                    <input type="password" class="form-control" id="current-password" name="current_password" placeholder="Current password">
                  </div>
                  <div class="mb-3">
                    <label for="new-password" class="form-label">New password</label>
                    <input type="password" class="form-control" id="new-password" name="new_password" placeholder="New password">
                  </div>
                  <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm new password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm new password">
                  </div>
                </section>

                <section class="mb-4">
                  <h2 class="h5 mb-3">Preferences</h2>
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="notifications" name="notifications" checked>
                    <label class="form-check-label" for="notifications">Receive notifications</label>
                  </div>
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="recommendations" name="recommendations" checked>
                    <label class="form-check-label" for="recommendations">Personalized recommendations</label>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Theme</label>
                    <div class="d-flex gap-3">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" id="theme-light" value="light" checked>
                        <label class="form-check-label" for="theme-light">Light</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" id="theme-dark" value="dark">
                        <label class="form-check-label" for="theme-dark">Dark</label>
                      </div>
                    </div>
                  </div>
                </section>

                <div class="d-flex flex-column flex-sm-row gap-3">
                  <button type="submit" class="btn btn-primary">Save changes</button>
                  <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                  <button type="button" class="btn btn-danger ms-auto">Delete account</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>