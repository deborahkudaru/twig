// config/twig.php
<?php
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
return new \Twig\Environment($loader, [
    'cache' => false, // Disable cache on free hosting
    'debug' => false, // Always false in production
    'auto_reload' => true, // Check for template changes
]);