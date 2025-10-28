<?php
namespace App\Service;

use PDO;
use Exception;

class TicketService {
    private $db;

    public function __construct() {
        // Railway database configuration
        $host = 'mysql.railway.internal';
        $port = '3306';
        $dbname = 'railway';
        $username = 'root';
        $password = 'bbsraMnjCgTmZtTtzPtLaoeyzHhShcpS';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        try {
            $this->db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 30,
            ]);
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAllTickets() {
        $stmt = $this->db->query("SELECT * FROM tickets ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getTicketById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countByStatus($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM tickets WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }

    public function createTicket($title, $status, $description) {
        $stmt = $this->db->prepare("INSERT INTO tickets (title, status, description, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$title, $status, $description]);
    }

    public function updateTicket($id, $title, $status, $description) {
        $stmt = $this->db->prepare("UPDATE tickets SET title = ?, status = ?, description = ? WHERE id = ?");
        return $stmt->execute([$title, $status, $description, $id]);
    }

    public function deleteTicket($id) {
        $stmt = $this->db->prepare("DELETE FROM tickets WHERE id = ?");
        return $stmt->execute([$id]);
    }
}