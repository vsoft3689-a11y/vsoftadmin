<?php
header("Content-Type: application/json");

include __DIR__ . '/../model/InquiryModel.php';
include __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$controller = new InquiryController();
$controller->handleRequest($action);

class InquiryController {
    private $db;
    private $model;

    public function __construct() {
        $this->db = (new Database())->connect();
        $this->model = new InquiryModel($this->db);
    }

    public function handleRequest($action) {
        switch ($action) {
            case "read":
                echo json_encode($this->model->read());
                break;

            case "create":
                $data = $_POST;
                $data['status'] = 'pending';
                echo json_encode(["success" => $this->model->create($data)]);
                break;

            case "updateStatus":
                $id = $_POST['id'];
                $status = $_POST['status'];
                echo json_encode(["success" => $this->model->updateStatus($id, $status)]);
                break;

            case "delete":
                $id = $_POST['id'];
                echo json_encode(["success" => $this->model->delete($id)]);
                break;

            case "getById":
                $id = $_GET['id'];
                echo json_encode($this->model->getById($id));
                break;

            default:
                echo json_encode(["error" => "Invalid action"]);
        }
    }
}
