<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Session;
use App\Service\TicketService;
use Exception;

class DashboardController {
    private $twig;
    private $ticketService;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
        $this->ticketService = new TicketService();
    }

    public function index() {
        try {
            $total = $this->ticketService->countByStatus('%'); // optional total if status filtering not used
            $open = $this->ticketService->countByStatus('open');
            $resolved = $this->ticketService->countByStatus('resolved');

            // fallback total if % doesn’t work
            $allTickets = $this->ticketService->getAllTickets();
            $total = count($allTickets);

            $stats = [
                'total' => $total,
                'open' => $open,
                'resolved' => $resolved,
            ];

            echo $this->twig->render('pages/dashboard.twig', [
                'stats' => $stats,
                'user' => Session::get('user'),
            ]);
        } catch (Exception $e) {
            echo $this->twig->render('pages/dashboard.twig', [
                'error' => $e->getMessage(),
                'stats' => ['total' => 0, 'open' => 0, 'resolved' => 0],
                'user' => Session::get('user'),
            ]);
        }
    }
}
