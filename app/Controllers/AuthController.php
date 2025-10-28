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

        if (empty($email) || empty($password)) {
            echo $this->twig->render('pages/login.twig', [
                'errors' => ['general' => 'All fields are required'],
                'form' => ['email' => $email],
            ]);
            return;
        }

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

    // 🟢 Show signup page
    public function showSignup() {
        echo $this->twig->render('pages/signup.twig', [
            'form' => [],
            'errors' => []
        ]);
    }

    // 🟢 Handle signup submission
    public function signup() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        $errors = [];

        if (!$name) $errors['name'] = 'Name is required';
        if (!$email || !str_contains($email, '@')) $errors['email'] = 'Invalid email';
        if (!$password || strlen($password) < 6) $errors['password'] = 'Password too short';
        if ($password !== $confirm) $errors['confirm'] = 'Passwords do not match';

        if (!empty($errors)) {
            echo $this->twig->render('pages/signup.twig', [
                'errors' => $errors,
                'form' => ['name' => $name, 'email' => $email]
            ]);
            return;
        }

        // Fake registration (replace with DB storage)
        Session::set('user', ['name' => $name, 'email' => $email]);
        header('Location: /dashboard');
        exit;
    }

    // Logout
    public function logout() {
        Session::destroy();
        header('Location: /auth/login?m=session_expired');
        exit;
    }
}
