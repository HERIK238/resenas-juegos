<?php
// Clase LogoutService
class LogoutService {
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        return true;
    }
}
?>