<?php
header("Content-Type: application/json");

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/PricingPackageModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new PricingPackageController();
$controller->handleRequest($action);

class PricingPackageController
{
    private $db;
    private $pricingpackageModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->pricingpackageModel = new PricingPackageModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $data = [
                    'service_type' => $_POST['service_type'],
                    'package_name' => $_POST['package_name'],
                    'description' => $_POST['description'],
                    'original_price' => $_POST['original_price'],
                    'discounted_price' => $_POST['discounted_price'] ?? null,
                    'duration' => $_POST['duration'],
                    'button_link' => $_POST['button_link'],
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0
                ];
                echo $this->pricingpackageModel->create($data)
                    ? json_encode(["status" => "success", "message" => "Service Added"])
                    : json_encode(["status" => "error", "message" => "Failed to Add"]);
                break;

            case "read":
                echo json_encode($this->pricingpackageModel->read());
                break;

            case "update":
                $id = $_POST['id'];
                $data = [
                    'service_type' => $_POST['service_type'],
                    'package_name' => $_POST['package_name'],
                    'description' => $_POST['description'],
                    'original_price' => $_POST['original_price'],
                    'discounted_price' => $_POST['discounted_price'] ?? null,
                    'duration' => $_POST['duration'],
                    'button_link' => $_POST['button_link'],
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0
                ];
                echo $this->pricingpackageModel->update($id, $data)
                    ? json_encode(["status" => "success", "message" => "Service Updated"])
                    : json_encode(["status" => "error", "message" => "Failed to Update"]);
                break;

            case "delete":
                $id = $_POST['id'];
                echo $this->pricingpackageModel->delete($id)
                    ? json_encode(["status" => "success", "message" => "Service Deleted"])
                    : json_encode(["status" => "error", "message" => "Failed to Delete"]);
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid Action"]);
        }
    }
}
