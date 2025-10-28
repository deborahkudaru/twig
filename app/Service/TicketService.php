<?php
namespace App\Service;

use PDO;
use Exception;

class TicketService {
    private $db;

    public function __construct() {
        $dsn = 'mysql:host=127.0.0.1;dbname=ticket_app;charset=utf8mb4';
        $username = 'root';
        $password = '';

        try {
            $this->db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    // Fetch all tickets
    public function getAllTickets() {
        $stmt = $this->db->query("SELECT * FROM tickets");
        return $stmt->fetchAll();
    }

    // Fetch ticket by ID
    public function getTicketById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Count tickets by status
    public function countByStatus($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM tickets WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }
}
