<?php
namespace App\Utils;

class Session {
    const SESSION_NAME = 'ticketapp_session';

    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function delete($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }

    public static function isAuthenticated() {
        $user = self::get('user');
        $exp = self::get('session_exp');
        
        if (!$user || !$exp) {
            return false;
        }
        
        if ($exp <= time()) {
            self::destroy();
            return false;
        }
        
        return true;
    }

    public static function getUser() {
        return self::get('user');
    }

    public static function setFlash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}