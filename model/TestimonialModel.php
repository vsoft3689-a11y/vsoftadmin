<?php
class TestimonialModel {
    private $conn;
    private $table = "testimonals";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (customer_name, customer_photo_path, designation, rating, review_text, display_order)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssisi", 
            $data['customer_name'], 
            $data['customer_photo_path'], 
            $data['designation'], 
            $data['rating'], 
            $data['review_text'], 
            $data['display_order']
        );
        return $stmt->execute();
    }

    public function read() {
        $result = $this->conn->query("SELECT * FROM $this->table ORDER BY display_order ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET customer_name=?, designation=?, rating=?, review_text=?, display_order=?, is_approved=?, customer_photo_path=IFNULL(?, customer_photo_path) WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssisiisi", 
            $data['customer_name'], 
            $data['designation'], 
            $data['rating'], 
            $data['review_text'], 
            $data['display_order'],
            $data['is_approved'],
            $data['customer_photo_path'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function toggleApproval($id, $newStatus) {
        $sql = "UPDATE $this->table SET is_approved=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $newStatus, $id);
        return $stmt->execute();
    }
}
