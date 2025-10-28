$stats = [
  'total' => 42,
  'open' => 10,
  'resolved' => 32
];

echo $twig->render('dashboard.twig', [
  'stats' => $stats,
  'error' => $error ?? null
]);
echo $twig->render('partials/dashboard_navbar.twig', [
  'session' => $_SESSION
]);
