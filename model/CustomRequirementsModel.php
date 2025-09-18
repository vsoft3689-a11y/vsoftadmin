<?php
class CustomRequirementsModel {
    private $conn;
    private $table="custom_requirements";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create project
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (user_id, title, description, technologies, status, document_path) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssss",
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['status'],
            $data['document_path']
        );
        return $stmt->execute();
    }

    // Read all projects
    public function readAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Read single project
    public function read($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update project
    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} 
            SET title=?, description=?, technologies=?, status=?, document_path=? 
            WHERE id=?
        ");
        $stmt->bind_param(
            "sssssi",
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['status'],
            $data['document_path'],
            $id
        );
        return $stmt->execute();
    }

    // Delete project
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
