<?php
class SiteConfigModel {
    private $conn;
    private $table = "site_configurations";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($key, $value) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (config_key, config_value) VALUES (?, ?)");
        $stmt->bind_param("ss", $key, $value);
        return $stmt->execute();
    }

    public function read() {
        $result = $this->conn->query("SELECT * FROM $this->table ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $key, $value) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET config_key=?, config_value=? WHERE id=?");
        $stmt->bind_param("ssi", $key, $value, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
