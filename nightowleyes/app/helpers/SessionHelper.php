<?php
class SessionHelper {
    public static function isLoggedIn() {
        // Đảm bảo session được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug
        if (isset($_SESSION['username'])) {
            error_log("SessionHelper::isLoggedIn() - Username is set: " . $_SESSION['username']);
            return true;
        } else {
            error_log("SessionHelper::isLoggedIn() - Username is NOT set");
            return false;
        }
    }
    
    public static function isAdmin() {
        // Đảm bảo session được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['username']) && $_SESSION['user_role'] === 'admin';
    }
} 