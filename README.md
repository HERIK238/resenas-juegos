authoreo Game 🎮 Reviews

[![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat)](LICENSE)

Plataforma web para crear y compartir reseñas de videojuegos con soporte de **autenticación Google OAuth**, gestión de usuarios y una base de datos escalable.

---

## ✨ Características

- 🔐 **Autenticación completa**
  - Registro y login de usuarios
  - Integración con Google OAuth 2.0
  - Gestión segura de sesiones

- 📝 **Sistema de reseñas**
  - Crear, editar y eliminar reseñas
  - Calificaciones de videojuegos
  - Dashboard de usuario personalizado

- 🎨 **UI/UX moderna**
  - Interfaz responsive con Bootstrap 5
  - Iconos Font Awesome
  - Animaciones AOS (Animate On Scroll)

- 🗄️ **Backend robusto**
  - API RESTful en PHP
  - Base de datos MySQL normalizada
  - Validación en servidor y cliente

---

## 🏗️ Stack Tecnológico

| Layer | Tecnología |
|-------|-----------|
| **Backend** | PHP 8+ |
| **Base de Datos** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Framework UI** | Bootstrap 5 |
| **Librerías** | jQuery, DataTables, Chart.js, SweetAlert2, Toastr |
| **Autenticación** | Google OAuth 2.0 |

---

## 🚀 Instalación

### Requisitos previos
- XAMPP (Apache + PHP 8+ + MySQL)
- Git
- Credenciales de Google OAuth

### Pasos

1. **Clonar repositorio**
   ```bash
   git clone https://github.com/HERIK238/resenas-juegos.git
   cd resenas-juegos
   ```

2. **Configurar variables de entorno**
   ```bash
   cp api/config/.env.example api/config/.env
   ```
   Edita `.env` con tus credenciales:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=plataforma_juegos
   GOOGLE_CLIENT_ID=tu_client_id
   GOOGLE_CLIENT_SECRET=tu_secret
   ```

3. **Importar base de datos**
   - Abre phpMyAdmin → `http://localhost/phpmyadmin`
   - Crea una nueva base de datos: `plataforma_juegos`
   - Importa el archivo SQL: `plataforma_juegos.sql`

4. **Iniciar servidor**
   - Abre XAMPP Control Panel
   - Inicia Apache y MySQL
   - Accede a `http://localhost/reseñas-juegos`

---

## 📡 API Endpoints

### Autenticación
| Endpoint | Método | Descripción |
|----------|--------|-------------|
| `/api/reg_user.php` | POST | Registrar nuevo usuario |
| `/api/auth_user.php` | POST | Login tradicional |
| `/api/google_login.php` | POST | Login con Google OAuth |
| `/api/check_session.php` | GET | Verificar sesión activa |
| `/api/logout.php` | POST | Cerrar sesión |

### Reseñas
| Endpoint | Método | Descripción |
|----------|--------|-------------|
| `/api/reviews.php` | GET | Listar todas las reseñas |
| `/api/review_create.php` | POST | Crear nueva reseña |
| `/api/review_edit.php` | PUT | Editar reseña |
| `/api/review_delete.php` | DELETE | Eliminar reseña |

---

## 📁 Estructura del Proyecto

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

## 🔒 Seguridad

- ✅ Variables sensibles en `.env` (nunca en git)
- ✅ Contraseñas hasheadas con bcrypt
- ✅ Validación de entrada en todos los endpoints
- ✅ Protección contra CSRF y SQL injection
- ✅ Logs de debug excluidos del repositorio

---

## 📋 Roadmap

- [ ] Implementar sistema de calificaciones y ratings
- [ ] Añadir búsqueda y filtros avanzados
- [ ] Dashboard de estadísticas para administradores
- [ ] Sistema de comentarios en reseñas
- [ ] Notificaciones por email

---

## 🤝 Cómo Contribuir

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcion`)
3. Commit los cambios (`git commit -m 'Agrega nueva función'`)
4. Push a la rama (`git push origin feature/nueva-funcion`)
5. Abre un Pull Request

Por favor, incluye:
- Descripción clara de los cambios
- Pasos para reproducir (si es un bug)
- Screenshots (si aplica)

---

## 📄 Licencia

Este proyecto está bajo licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 👨‍💻 Autor

**HERIK**  
📧 Contact: herikbernalgomez@gmail.com
🔗 GitHub: [@HERIK238](https://github.com/HERIK238)

---

## 📞 Soporte

¿Encontraste un bug? ¿Tienes una sugerencia?  
[Abre un issue](https://github.com/HERIK238/resenas-juegos/issues/new) 🐛
