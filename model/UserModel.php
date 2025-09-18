<?php
class UserModel
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function create($data)
    {
        $query = "INSERT INTO $this->table (name, email, password_hash, phone, college, branch, year) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bind_param("sssssss", $data['name'], $data['email'], $password, $data['phone'], $data['college'], $data['branch'], $data['year']);
        return $stmt->execute();
    }

    // READ
    public function read()
    {
        $query = "SELECT id, name, email, phone, college, branch, year, created_at, status FROM $this->table ORDER BY id DESC";
        $result = $this->conn->query($query);
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    }

    // UPDATE
    public function update($data)
    {
        $query = "UPDATE $this->table SET name=?, email=?, phone=?, college=?, branch=?, year=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $data['name'], $data['email'], $data['phone'], $data['college'], $data['branch'], $data['year'], $data['id']);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // TOGGLE STATUS
    public function toggleStatus($id, $status)
    {
        $query = "UPDATE $this->table SET status=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

     // VERIFY email
    public function verifyEmail($id) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET email_verified_at=NOW() WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // CHANGE password
    public function updatePassword($id, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("UPDATE $this->table SET password_hash=? WHERE id=?");
        $stmt->bind_param("si", $passwordHash, $id);
        return $stmt->execute();
    }
}
