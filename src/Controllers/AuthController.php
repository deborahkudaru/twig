<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Session;

class AuthController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    // 🟢 Show login page
    public function showLogin() {
        $message = $_GET['m'] ?? '';
        echo $this->twig->render('pages/login.twig', [
            'msg' => $message
        ]);
    }

    // 🟢 Handle login submission
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Simple validation
        if (empty($email) || empty($password)) {
            echo $this->twig->render('pages/login.twig', [
                'errors' => ['general' => 'All fields are required'],
                'form' => ['email' => $email],
            ]);
            return;
        }

        // Fake authentication (you can replace with DB check)
        if ($email === 'admin@example.com' && $password === 'password123') {
            Session::set('user', ['email' => $email]);
            header('Location: /dashboard');
            exit;
        } else {
            echo $this->twig->render('pages/login.twig', [
                'errors' => ['password' => 'Invalid credentials'],
                'form' => ['email' => $email],
            ]);
        }
    }

    // 🟢 Logout
    public function logout() {
        Session::destroy();
        header('Location: /auth/login?m=session_expired');
        exit;
    }
}
