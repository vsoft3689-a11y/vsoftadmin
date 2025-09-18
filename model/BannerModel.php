<?php
class BannerModel {
    private $conn;
    private $table = "banners";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (image_path, tagline, sub_text, cta_button_text, cta_button_link, display_order) 
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", 
            $data['image_path'], 
            $data['tagline'], 
            $data['sub_text'], 
            $data['cta_button_text'], 
            $data['cta_button_link'], 
            $data['display_order']
        );
        return $stmt->execute();
    }

    public function read() {
        $result = $this->conn->query("SELECT * FROM {$this->table} ORDER BY display_order ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function toggleStatus($id, $is_active) {
        $sql = "UPDATE {$this->table} SET is_active=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $is_active, $id);
        return $stmt->execute();
    }
}
