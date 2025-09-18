<?php
header("Content-Type: application/json");

include __DIR__ . '/../model/ProjectModel.php';
include __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$controller = new ProjectController();
$controller->handleRequest($action);

class ProjectController
{
    private $db;
    private $projectModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->projectModel = new ProjectModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $data = $_POST;
                $data['file_path_abstract'] = $this->uploadFile('abstract');
                $data['file_path_basepaper'] = $this->uploadFile('basepaper');
                echo json_encode(["success" => $this->projectModel->create($data)]);
                break;

            case "read":
                echo json_encode($this->projectModel->read());
                break;

            case "getById":
                $id = $_GET['id'];
                echo json_encode($this->projectModel->getById($id));
                break;

            case "update":
                $id = $_POST['id'];
                $data = $_POST;
                $data['file_path_abstract'] = !empty($_FILES['abstract']['name'])
                    ? $this->uploadFile('abstract')
                    : null;

                $data['file_path_basepaper'] = !empty($_FILES['basepaper']['name'])
                    ? $this->uploadFile('basepaper')
                    : null;

                echo json_encode(["success" => $this->projectModel->update($id, $data)]);
                break;

            case "delete":
                $id = $_POST['id'];
                echo json_encode(["success" => $this->projectModel->delete($id)]);
                break;

            default:
                echo json_encode(["error" => "Invalid action"]);
                break;
        }
    }

    private function uploadExcel(){

    }

    private function uploadFile($fieldName, $oldPath = null)
    {
        $uploadDir = "../uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!empty($_FILES[$fieldName]['name'])) {
            $filePath = $uploadDir . time() . "_" . basename($_FILES[$fieldName]['name']);
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath);
            return $filePath;
        }
        return $oldPath;
    }
}
