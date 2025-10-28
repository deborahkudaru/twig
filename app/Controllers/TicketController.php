<?php
namespace App\Controllers;

use App\Service\TicketService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TicketController {
    private $twig;
    private $ticketService;

    public function __construct() {
        // Setup Twig directly
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);

        // Initialize the TicketService
        $this->ticketService = new TicketService();
    }

    public function index() {
        $tickets = $this->ticketService->getAllTickets();

        echo $this->twig->render('pages/tickets.twig', [
            'tickets' => $tickets,
            'error' => null
        ]);
    }

    public function showCreateForm() {
        echo $this->twig->render('pages/ticket_form.twig', [
            'action' => '/tickets/new',
            'ticket' => ['title' => '', 'status' => '', 'description' => ''],
            'errors' => [],
            'cancel_url' => '/tickets'
        ]);
    }

    public function create() {
        $title = trim($_POST['title'] ?? '');
        $status = $_POST['status'] ?? 'open';
        $description = trim($_POST['description'] ?? '');

        $errors = [];

        if (strlen($title) < 3) $errors['title'] = 'Title must be at least 3 characters';
        if (!in_array($status, ['open', 'in_progress', 'closed'])) $errors['status'] = 'Invalid status';
        if (empty($description)) $errors['description'] = 'Description is required';

        if (!empty($errors)) {
            echo $this->twig->render('pages/ticket_form.twig', [
                'action' => '/tickets/new',
                'ticket' => ['title' => $title, 'status' => $status, 'description' => $description],
                'errors' => $errors,
                'cancel_url' => '/tickets'
            ]);
            return;
        }

        $this->ticketService->createTicket($title, $status, $description);

        header('Location: /tickets');
        exit;
    }

    public function edit($id)
{
    $ticket = $this->ticketService->getTicketById($id);

    echo $this->twig->render('pages/ticket_form.twig', [
        'action' => "/tickets/update/$id",
        'ticket' => $ticket,
        'errors' => [],
        'cancel_url' => '/tickets'
    ]);
}


    public function update($id) {
        $title = trim($_POST['title'] ?? '');
        $status = $_POST['status'] ?? 'open';
        $description = trim($_POST['description'] ?? '');

        $this->ticketService->updateTicket($id, $title, $status, $description);

        header('Location: /tickets');
        exit;
    }

    public function delete($id) {
        $this->ticketService->deleteTicket($id);
        header('Location: /tickets');
        exit;
    }
}
