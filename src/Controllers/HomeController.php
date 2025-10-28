<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    public function index() {
        echo $this->twig->render('pages/home.twig', [
            'title' => 'Welcome to Ticket Tocket',
        ]);
    }
}
