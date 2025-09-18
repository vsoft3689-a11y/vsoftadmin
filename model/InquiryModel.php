<?php
class InquiryModel {
    private $conn;
    private $table = "inquiries";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function read() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $inquiries = [];
        while ($row = $result->fetch_assoc()) {
            $inquiries[] = $row;
        }
        return $inquiries;
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, email, phone, subject, message, type, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssss",
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $data['type'],
            $data['status']
        );
        return $stmt->execute();
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
