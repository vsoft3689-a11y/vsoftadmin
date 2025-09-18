<?php
header("Content-Type: application/json");

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/TestimonialModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new TestimonialController();
$controller->handleRequest($action);

class TestimonialController
{
    private $db;
    private $testimonalModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->testimonalModel = new TestimonialModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $photoPath = null;
                if (!empty($_FILES['customer_photo']['name'])) {
                    $targetDir = __DIR__ . "/../uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    $photoPath = "uploads/" . time() . "_" . basename($_FILES['customer_photo']['name']);
                    move_uploaded_file($_FILES['customer_photo']['tmp_name'], __DIR__ . "/../" . $photoPath);
                }

                $data = [
                    "customer_name" => $_POST['customer_name'],
                    "customer_photo_path" => $photoPath,
                    "designation" => $_POST['designation'],
                    "rating" => $_POST['rating'],
                    "review_text" => $_POST['review_text'],
                    "display_order" => $_POST['display_order']
                ];

                echo $this->testimonalModel->create($data)
                    ? json_encode(["status" => "success", "message" => "Review added"])
                    : json_encode(["status" => "error", "message" => "Failed to add"]);
                break;

            case "read":
                echo json_encode($this->testimonalModel->read());
                break;

            case "update":
                $photoPath = null;
                if (!empty($_FILES['customer_photo']['name'])) {
                    $targetDir = __DIR__ . "/../uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    $photoPath = "uploads/" . time() . "_" . basename($_FILES['customer_photo']['name']);
                    move_uploaded_file($_FILES['customer_photo']['tmp_name'], __DIR__ . "/../" . $photoPath);
                }

                $data = [
                    "customer_name" => $_POST['customer_name'],
                    "designation" => $_POST['designation'],
                    "rating" => $_POST['rating'],
                    "review_text" => $_POST['review_text'],
                    "display_order" => $_POST['display_order'],
                    "is_approved" => $_POST['is_approved'],
                    "customer_photo_path" => $photoPath
                ];

                echo $this->testimonalModel->update($_POST['id'], $data)
                    ? json_encode(["status" => "success", "message" => "Review updated"])
                    : json_encode(["status" => "error", "message" => "Failed to update"]);
                break;

            case "delete":
                echo $this->testimonalModel->delete($_POST['id'])
                    ? json_encode(["status" => "success", "message" => "Review deleted"])
                    : json_encode(["status" => "error", "message" => "Failed to delete"]);
                break;

            case "toggle_approval":
                $id = $_POST['id'];
                $current = $_POST['current'];
                $newStatus = $current == 1 ? 0 : 1;

                echo $this->testimonalModel->toggleApproval($id, $newStatus)
                    ? json_encode(["status" => "success", "message" => "Approval toggled", "newStatus" => $newStatus])
                    : json_encode(["status" => "error", "message" => "Failed to toggle"]);
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
    }
}
