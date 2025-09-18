<?php
header("Content-Type: application/json");

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/BannerModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new BannerController();
$controller->handleRequest($action);


class BannerController
{
    private $db;
    private $bannerModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->bannerModel = new BannerModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {

            case "create":
                $this->createBanner();
                break;

            case "read":
                $this->readBanners();
                break;

            case "delete":
                $this->deleteBanner();
                break;

            case "toggle_status":
                $this->toggleStatus();
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
                return;
        }
    }

    private function createBanner()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename = time() . "_" . basename($_FILES['image']['name']);
            $target = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $data = [
                    'image_path' => "uploads/" . $filename,
                    'tagline' => $_POST['tagline'] ?? null,
                    'sub_text' => $_POST['sub_text'] ?? null,
                    'cta_button_text' => $_POST['cta_button_text'] ?? null,
                    'cta_button_link' => $_POST['cta_button_link'] ?? null,
                    'display_order' => $_POST['display_order'] ?? 0
                ];

                if ($this->bannerModel->create($data)) {
                    echo json_encode(["status" => "success", "message" => "Banner created successfully"]);
                    return;
                }
            }
        }

        echo json_encode(["status" => "error", "message" => "Failed to create banner"]);
    }

    private function readBanners()
    {
        $banners = $this->bannerModel->read();
        echo json_encode($banners);
    }

    private function deleteBanner()
    {
        $id = $_POST['id'] ?? 0;
        if ($this->bannerModel->delete($id)) {
            echo json_encode(["status" => "success", "message" => "Banner deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete"]);
        }
    }

    private function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $is_active = $_POST['is_active'] ?? 0;
        if ($this->bannerModel->toggleStatus($id, $is_active)) {
            echo json_encode(["status" => "success", "message" => "Status updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update status"]);
        }
    }
}
