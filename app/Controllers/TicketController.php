try {
  $tickets = $ticketService->getAllTickets(); // your function to fetch all tickets
} catch (Exception $e) {
  $error = $e->getMessage();
}

echo $twig->render('tickets.twig', [
  'tickets' => $tickets ?? [],
  'error' => $error ?? null,
]);

// controller/tickets.php
$errors = [];
$title = trim($_POST['title'] ?? '');
$status = $_POST['status'] ?? 'open';
$description = trim($_POST['description'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (strlen($title) < 3) $errors['title'] = 'Title must be at least 3 characters';
  if (!in_array($status, ['open','in_progress','closed'])) $errors['status'] = 'Status must be one of: open, in_progress, closed';
  if (strlen($description) > 2000) $errors['description'] = 'Description too long';

  if (empty($errors)) {
    if (isset($_GET['id'])) {
      $ticketService->updateTicket($_GET['id'], $title, $status, $description);
    } else {
      $ticketService->createTicket($title, $status, $description);
    }
    header('Location: /tickets');
    exit;
  }
}

echo $twig->render('_ticket_form.twig', [
  'ticket' => [
    'title' => $title,
    'status' => $status,
    'description' => $description,
  ],
  'errors' => $errors,
  'action' => $_SERVER['REQUEST_URI'],
  'cancel_url' => '/tickets',
]);
