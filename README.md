redeams Reviews

> Plataforma de reseñas de videojuegos — backend en PHP + MySQL, frontend con Bootstrap y JS.

## Estado
- Proyecto local con API en `api/`, vistas en `views/` y assets en `css/` y `js/`.

## Requisitos
- XAMPP (Apache + PHP + MySQL)
- PHP 8+

## Instalación rápida
1. Clona el repositorio en la carpeta de tu servidor local (ej: `C:\xampp\htdocs\`)
2. Copia `api/config/.env.example` a `api/config/.env` y completa las credenciales
3. Importa la base de datos `plataforma_juegos.sql` en MySQL (phpMyAdmin o CLI)
4. Asegúrate de que `htdocs/reseñas-juegos` sea accesible vía `http://localhost/reseñas-juegos`

## Endpoints principales
- `api/reg_user.php` — Registro de usuario (POST)
- `api/auth_user.php` — Login (POST)
- `api/google_login.php` — Login con Google (POST)
- `api/check_session.php` — Verificar sesión (GET)
- `api/logout.php` — Cerrar sesión (POST)

## Notas de seguridad
- No comitear `api/config/.env` ni archivos de logs (`api/debug_log.txt`). Ya están en `.gitignore`.

## Contribuir
- Abrir issue o PR con descripción y pasos para reproducir.

## Contacto
- Autor: HERIK
