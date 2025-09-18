<?php
class PricingPackageModel {
    private $conn;
    private $table = "pricing_packages";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (service_type, package_name, description, original_price, discounted_price, duration, button_link, is_featured)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $data['service_type'],
            $data['package_name'],
            $data['description'],
            $data['original_price'],
            $data['discounted_price'],
            $data['duration'],
            $data['button_link'],
            $data['is_featured']
        );
        return $stmt->execute();
    }

    public function read() {
        $result = $this->conn->query("SELECT * FROM $this->table ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function update($id, $data) {
        $sql = "UPDATE $this->table SET service_type=?, package_name=?, description=?, original_price=?, discounted_price=?, duration=?, button_link=?, is_featured=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssii",
            $data['service_type'],
            $data['package_name'],
            $data['description'],
            $data['original_price'],
            $data['discounted_price'],
            $data['duration'],
            $data['button_link'],
            $data['is_featured'],
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
}
