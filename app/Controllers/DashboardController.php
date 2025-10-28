<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Session;


class DashboardController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader([
    __DIR__ . '/../../templates',
    __DIR__ . '/../../templates/layout',
    __DIR__ . '/../../templates/pages',
    __DIR__ . '/../../templates/partials'
]);

        $this->twig = new Environment($loader);
    }

    public function index() {
        // Dummy stats data
        $stats = [
            'total' => 42,
            'open' => 10,
            'resolved' => 32
        ];

        // Render dashboard page
        echo $this->twig->render('pages/dashboard.twig', [
            'stats' => $stats,
            'user' => Session::get('user')
        ]);
    }
}
