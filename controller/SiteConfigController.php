<?php
header("Content-Type: application/json");

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/SiteConfigModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new SiteConfigController();
$controller->handleRequest($action);

class SiteConfigController
{
    private $db;
    private $siteModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->siteModel = new SiteConfigModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $key = $_POST['config_key'] ?? null;
                $value = $_POST['config_value'] ?? null;
                if ($this->siteModel->create($key, $value)) {
                    echo json_encode(["status" => "success", "message" => "Config added"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to add config"]);
                }
                break;

            case "read":
                echo json_encode($this->siteModel->read());
                break;

            case "update":
                $id = $_POST['id'] ?? 0;
                $key = $_POST['config_key'] ?? null;
                $value = $_POST['config_value'] ?? null;
                if ($this->siteModel->update($id, $key, $value)) {
                    echo json_encode(["status" => "success", "message" => "Config updated"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update config"]);
                }
                break;

            case "delete":
                $id = $_POST['id'] ?? 0;
                if ($this->siteModel->delete($id)) {
                    echo json_encode(["status" => "success", "message" => "Config deleted"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to delete config"]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
    }
}
