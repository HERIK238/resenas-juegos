# Game Reviews 🎮

[![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat)](LICENSE)

A web platform for creating and sharing video game reviews with **Google OAuth** authentication, personalized recommendations, and a scalable MVC backend.

---

## ✨ Features

- 🔐 **Full Authentication**
  - User registration and login
  - Google OAuth 2.0 integration
  - Secure session management with CSRF protection

- 📝 **Reviews System**
  - Create and delete personal reviews
  - Personalized user dashboard showing all reviews
  - Per-card delete confirmation with SweetAlert2

- 🎯 **Recommendations**
  - Game recommendations based on user preferred genres
  - Full game catalog with search

- ⚙️ **Settings**
  - User profile configuration

- 🗄️ **Robust Backend**
  - RESTful PHP API with MVC architecture
  - Normalized MySQL database
  - PDO prepared statements (SQL injection protection)
  - XSS protection via textContent and server-side escaping

---

## 🏗️ Technology Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **UI Framework** | Bootstrap 5 |
| **Libraries** | SweetAlert2, Bootstrap Icons |
| **Authentication** | Google OAuth 2.0 |

---

## 🚀 Installation

### Prerequisites
- XAMPP (Apache + PHP 8+ + MySQL)
- Git
- Google OAuth credentials

### Steps

1. **Clone repository**
   ```bash
   git clone https://github.com/HERIK238/resenas-juegos.git
   cd resenas-juegos
   ```

2. **Configure environment variables**
   ```bash
   cp api/config/.env.example api/config/.env
   ```
   Edit `.env` with your credentials:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=plataforma_juegos
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   ```

3. **Import the database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Create a new database: `plataforma_juegos`
   - Import the SQL file: `plataforma_juegos.sql`

4. **Start the server**
   - Open XAMPP Control Panel
   - Start Apache and MySQL
   - Visit `http://localhost/reseñas-juegos`

---

## 📡 API Endpoints

### Authentication
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/reg_user.php` | POST | Register a new user |
| `/api/auth_user.php` | POST | Traditional login |
| `/api/google_login.php` | POST | Google OAuth login |
| `/api/check_session.php` | GET | Check active session |
| `/api/logout.php` | POST | Log out |

### Reviews
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/reviews.php` | GET | List user reviews |
| `/api/reviews.php` | POST | Create a new review |
| `/api/delete_review.php` | DELETE | Delete a review |
| `/api/obtener_reseñas.php` | GET | Get all public reviews |

### Catalog & Recommendations
| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/catalog.php` | GET | List games and genres |
| `/api/recommendations.php` | GET | Get personalized recommendations |

---

## 📁 Project Structure

```
reseñas-juegos/
├── api/
│   ├── config/
│   │   ├── .env
│   │   ├── .htaccess
│   │   ├── csrf_check.php
│   │   ├── db.php
│   │   └── env.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DeleteReviewController.php
│   │   ├── ListUserController.php
│   │   ├── LogoutController.php
│   │   ├── ModalController.php
│   │   ├── obtener_reseñaController.php
│   │   └── ReviewsController.php
│   ├── models/
│   │   ├── DeleteReviewModel.php
│   │   ├── ModalAuth.php
│   │   ├── obtener_reseñaModels.php
│   │   ├── ReviewsModels.php
│   │   ├── UserAuth.php
│   │   └── UserList.php
│   ├── services/
│   │   ├── AuthService.php
│   │   ├── DeleteReviewService.php
│   │   ├── LogoutService.php
│   │   ├── ModalService.php
│   │   ├── obtener_reseñaService.php
│   │   ├── ReviewsService.php
│   │   └── UserListService.php
│   ├── middleware/
│   │   └── auth.php
│   ├── core/
│   │   └── DBConfig.php
│   ├── auth_user.php
│   ├── catalog.php
│   ├── check_session.php
│   ├── delete_review.php
│   ├── google_login.php
│   ├── logout.php
│   ├── obtener_reseñas.php
│   ├── recommendations.php
│   ├── reg_user.php
│   └── reviews.php
├── views/
│   ├── dashboard.php
│   ├── recommendations.php
│   ├── reviews.php
│   └── settings.php
├── css/
│   ├── dashboard.css
│   ├── reviews.css
│   └── settings.css
├── js/
│   ├── dashboard.js
│   ├── main.js
│   ├── recommendations.js
│   ├── reviews.js
│   └── settings.js
├── assets/
│   ├── logo.png
│   ├── fonts/
│   ├── icons/
│   ├── imagenes/
│   └── sounds/
├── dist/
│   ├── bootstrap/
│   ├── sweetalert2/
│   └── ...
└── README.md
```

---

## 🔒 Security

- ✅ Sensitive variables stored in `.env` (never in git)
- ✅ Passwords hashed with bcrypt
- ✅ PDO prepared statements against SQL injection
- ✅ CSRF protection on POST and DELETE requests
- ✅ XSS prevention with textContent and server-side escaping
- ✅ Sessions with HttpOnly cookies and SameSite=Lax
- ✅ Error display disabled in production

---

## 📋 Roadmap

- [ ] Edit reviews
- [ ] Add advanced search and filters
- [ ] Create admin statistics dashboard
- [ ] Add comments to reviews
- [ ] Add email notifications

---

## 🤝 Contribution

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under MIT. See the `LICENSE` file for details.

---

## 👨‍💻 Author

**HERIK**  
📧 Contact: herikbernalgomez@gmail.com  
🔗 GitHub: [@HERIK238](https://github.com/HERIK238)

---

## 📞 Support

Found a bug or have a suggestion?  
[Open an issue](https://github.com/HERIK238/resenas-juegos/issues/new) 🐛
