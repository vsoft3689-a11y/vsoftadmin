<?php
header("Content-Type: application/json");
include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/CustomRequirementsModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new CustomRequirementsController();
$controller->handleRequest($action);

class CustomRequirementsController
{
    private $db;
    private $customModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->customModel = new CustomRequirementsModel($this->db);
    }

    public function handleRequest($action)
    {

        switch ($action) {
            case "create":
                $data = [
                    "user_id" => $_POST['user_id'],
                    "title" => $_POST['title'],
                    "description" => $_POST['description'] ?? null,
                    "technologies" => $_POST['technologies'] ?? null,
                    "status" => $_POST['status'] ?? 'pending',
                    "document_path" => null
                ];

                // file upload (optional)
                if (!empty($_FILES['document']['name'])) {
                    $targetDir = __DIR__ . "/../uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir);
                    $fileName = time() . "_" . basename($_FILES['document']['name']);
                    $targetFile = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFile)) {
                        $data['document_path'] = "uploads/" . $fileName;
                    }
                }

                echo $this->customModel->create($data)
                    ? json_encode(["status" => "success", "message" => "Project created"])
                    : json_encode(["status" => "error", "message" => "Failed to create"]);
                break;

            case "read":
                echo json_encode($this->customModel->readAll());
                break;

            case "read_single":
                $id = $_GET['id'] ?? 0;
                echo json_encode($this->customModel->read($id));
                break;

            case "update":
                $id = $_POST['id'];
                $data = [
                    "title" => $_POST['title'],
                    "description" => $_POST['description'] ?? null,
                    "technologies" => $_POST['technologies'] ?? null,
                    "status" => $_POST['status'] ?? 'pending',
                    "document_path" => $_POST['document_path'] ?? null
                ];

                // file upload (optional update)
                if (!empty($_FILES['document']['name'])) {
                    $targetDir = __DIR__ . "/../uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir);
                    $fileName = time() . "_" . basename($_FILES['document']['name']);
                    $targetFile = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFile)) {
                        $data['document_path'] = "uploads/" . $fileName;
                    }
                }

                echo $this->customModel->update($id, $data)
                    ? json_encode(["status" => "success", "message" => "Project updated"])
                    : json_encode(["status" => "error", "message" => "Failed to update"]);
                break;

            case "delete":
                $id = $_POST['id'];
                echo $this->customModel->delete($id)
                    ? json_encode(["status" => "success", "message" => "Project deleted"])
                    : json_encode(["status" => "error", "message" => "Failed to delete"]);
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
    }
}
