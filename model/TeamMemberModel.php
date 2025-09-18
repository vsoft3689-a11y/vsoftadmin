<?php
class TeamMemberModel {
    private $conn;
    private $table = "team_members";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data, $file) {
        $profile_picture_path = null;

        if (isset($file['profile_picture']) && $file['profile_picture']['error'] == 0) {
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir);
            $filename = time() . "_" . basename($file['profile_picture']['name']);
            $target = $uploadDir . $filename;
            if (move_uploaded_file($file['profile_picture']['tmp_name'], $target)) {
                $profile_picture_path = "uploads/" . $filename;
            }
        }

        $sql = "INSERT INTO {$this->table} 
            (name, designation, bio, profile_picture_path, social_facebook, social_twitter, social_linkedin, display_order) 
            VALUES (?,?,?,?,?,?,?,?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssi",
            $data['name'],
            $data['designation'],
            $data['bio'],
            $profile_picture_path,
            $data['social_facebook'],
            $data['social_twitter'],
            $data['social_linkedin'],
            $data['display_order']
        );

        return $stmt->execute();
    }

    public function read() {
        $sql = "SELECT * FROM {$this->table} ORDER BY display_order ASC";
        $result = $this->conn->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    public function update($data, $file) {
        $profile_picture_sql = "";
        $profile_picture_path = null;

        if (isset($file['profile_picture']) && $file['profile_picture']['error'] == 0) {
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir);
            $filename = time() . "_" . basename($file['profile_picture']['name']);
            $target = $uploadDir . $filename;
            if (move_uploaded_file($file['profile_picture']['tmp_name'], $target)) {
                $profile_picture_path = "uploads/" . $filename;
                $profile_picture_sql = ", profile_picture_path='$profile_picture_path'";
            }
        }

        $sql = "UPDATE {$this->table} SET 
                name='{$data['name']}', 
                designation='{$data['designation']}', 
                bio='{$data['bio']}', 
                social_facebook='{$data['social_facebook']}', 
                social_twitter='{$data['social_twitter']}', 
                social_linkedin='{$data['social_linkedin']}', 
                display_order={$data['display_order']}
                $profile_picture_sql
            WHERE id={$data['id']}";

        return $this->conn->query($sql);
    }

    public function delete($id) {
        return $this->conn->query("DELETE FROM {$this->table} WHERE id=$id");
    }

    public function toggleStatus($id, $is_active) {
        return $this->conn->query("UPDATE {$this->table} SET is_active=$is_active WHERE id=$id");
    }
}
