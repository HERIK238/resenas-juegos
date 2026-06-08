authoreo Game 🎮 Reviews

[![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat)](LICENSE)

A web platform for creating and sharing video game reviews with **Google OAuth** authentication, user management, and a scalable database.

---

## ✨ Features

- 🔐 **Full Authentication**
  - User registration and login
  - Google OAuth 2.0 integration
  - Secure session management

- 📝 **Reviews System**
  - Create, edit, and delete reviews
  - Game ratings
  - Personalized user dashboard

- 🎨 **Modern UI/UX**
  - Responsive interface with Bootstrap 5
  - Font Awesome icons
  - AOS animations (Animate On Scroll)

- 🗄️ **Robust Backend**
  - RESTful PHP API
  - Normalized MySQL database
  - Server-side and client-side validation

---

## 🏗️ Technology Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **UI Framework** | Bootstrap 5 |
| **Libraries** | jQuery, DataTables, Chart.js, SweetAlert2, Toastr |
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
| `/api/reviews.php` | GET | List all reviews |
| `/api/review_create.php` | POST | Create a new review |
| `/api/review_edit.php` | PUT | Edit a review |
| `/api/review_delete.php` | DELETE | Delete a review |

---

## 📁 Project Structure

```
reseñas-juegos/
├── api/
│   ├── config/
│   │   ├── .env.example
│   │   └── db.php
│   ├── auth_user.php
│   ├── reg_user.php
│   ├── google_login.php
│   └── ...
├── views/
│   ├── home.php
│   ├── dashboard.php
│   └── ...
├── css/
│   └── style.css
├── js/
│   ├── main.js
│   └── dashboard.js
├── dist/
│   ├── bootstrap/
│   ├── jquery/
│   ├── datatables/
│   └── ...
└── README.md
```

---

## 🔒 Security

- ✅ Sensitive variables stored in `.env` (never in git)
- ✅ Passwords hashed with bcrypt
- ✅ Input validation on all endpoints
- ✅ CSRF and SQL injection protection
- ✅ Debug logs excluded from the repository

---

## 📋 Roadmap

- [ ] Add a ratings system
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

Please include:
- A clear description of the changes
- Steps to reproduce (if fixing a bug)
- Screenshots (if applicable)

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
