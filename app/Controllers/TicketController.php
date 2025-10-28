<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Session;

class TicketController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    // 🟢 Show all tickets
    public function index() {
        try {
            // Example: Fetch tickets (replace with actual DB/service)
            $tickets = [
                ['id' => 1, 'title' => 'Login Issue', 'status' => 'open', 'description' => 'Cannot log in with my credentials.'],
                ['id' => 2, 'title' => 'Payment Delay', 'status' => 'in_progress', 'description' => 'My payment is stuck.'],
                ['id' => 3, 'title' => 'Bug in Dashboard', 'status' => 'closed', 'description' => 'Dashboard not loading after update.']
            ];
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        echo $this->twig->render('pages/tickets.twig', [
            'tickets' => $tickets ?? [],
            'error' => $error ?? null,
            'user' => Session::get('user')
        ]);
    }

    // 🟢 Handle create ticket form
    public function create() {
        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $status = $_POST['status'] ?? 'open';
        $description = trim($_POST['description'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (strlen($title) < 3) $errors['title'] = 'Title must be at least 3 characters';
            if (!in_array($status, ['open','in_progress','closed'])) $errors['status'] = 'Invalid status';
            if (strlen($description) > 2000) $errors['description'] = 'Description too long';

            if (empty($errors)) {
                // Example placeholder — replace with DB save later
                // $ticketService->createTicket($title, $status, $description);
                header('Location: /tickets');
                exit;
            }
        }

        echo $this->twig->render('pages/ticket_form.twig', [
            'ticket' => [
                'title' => $title,
                'status' => $status,
                'description' => $description,
            ],
            'errors' => $errors,
            'action' => '/tickets/create',
            'cancel_url' => '/tickets',
            'user' => Session::get('user')
        ]);
    }

    public function showCreateForm() {
    echo $this->twig->render('pages/ticket_form.twig', [
        'action' => '/tickets/new',
        'errors' => [],
        'ticket' => [],
    ]);
}

    // 🟢 Stub for update ticket (can extend later)
    public function update() {
        echo "Update ticket logic coming soon.";
    }

    // 🟢 Stub for delete ticket
    public function delete() {
        echo "Delete ticket logic coming soon.";
    }
}
