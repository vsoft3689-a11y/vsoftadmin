<?php
class ProjectModel
{
    private $conn;
    private $table = "projects";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Create
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
        (degree, branch, type, domain, title, description, technologies, price, youtube_url, file_path_abstract, file_path_basepaper) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssss",
            $data['degree'],
            $data['branch'],
            $data['type'],
            $data['domain'],
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['price'],
            $data['youtube_url'],
            $data['file_path_abstract'],
            $data['file_path_basepaper']
        );
        return $stmt->execute();
    }

    public function existsByTitle($title)
    {
        $query = "SELECT id FROM " . $this->table . " WHERE title = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        return false;
    }

    public function updateByTitle($title, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET degree = ?, branch = ?, type = ?, domain = ?, description = ?, 
                      technologies = ?, price = ?, youtube_url = ?, file_path_abstract = ?, file_path_basepaper = ?
                  WHERE title = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sssssssssss",
            $data['degree'],
            $data['branch'],
            $data['type'],
            $data['domain'],
            $data['description'],
            $data['technologies'],
            $data['price'],
            $data['youtube_url'],
            $data['file_path_abstract'],
            $data['file_path_basepaper'],
            $title
        );
        return $stmt->execute();
    }

    // Read All
    public function read()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        return $projects;
    }

    // Read by ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Update
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
        SET degree=?, branch=?, type=?, domain=?, title=?, description=?, technologies=?, price=?, youtube_url=?, file_path_abstract=?, file_path_basepaper=? 
        WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssssi",
            $data['degree'],
            $data['branch'],
            $data['type'],
            $data['domain'],
            $data['title'],
            $data['description'],
            $data['technologies'],
            $data['price'],
            $data['youtube_url'],
            $data['file_path_abstract'],
            $data['file_path_basepaper'],
            $id
        );
        return $stmt->execute();
    }

    // Delete
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
