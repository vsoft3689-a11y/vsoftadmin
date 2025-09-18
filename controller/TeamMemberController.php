<?php
header("Content-Type: application/json");
require_once "../config/Database.php";
require_once "../model/TeamMemberModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new TeamMemberController();
$controller->handleRequest($action);

class TeamMemberController
{
    private $db;
    private $teamModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->teamModel = new TeamMemberModel($this->db);
    }

    public function handleRequest($action)
    {

        switch ($action) {
            case "create":
                $success = $this->teamModel->create($_POST, $_FILES);
                echo json_encode($success ? ["status" => "success", "message" => "Member added"]
                    : ["status" => "error", "message" => "Failed to add"]);
                break;

            case "read":
                echo json_encode($this->teamModel->read());
                break;

            case "update":
                $success = $this->teamModel->update($_POST, $_FILES);
                echo json_encode($success ? ["status" => "success", "message" => "Member updated"]
                    : ["status" => "error", "message" => "Update failed"]);
                break;

            case "delete":
                $success = $this->teamModel->delete($_POST['id']);
                echo json_encode($success ? ["status" => "success", "message" => "Member deleted"]
                    : ["status" => "error", "message" => "Delete failed"]);
                break;

            case "toggle_status":
                $success = $this->teamModel->toggleStatus($_POST['id'], $_POST['is_active']);
                echo json_encode($success ? ["status" => "success", "message" => "Status changed"]
                    : ["status" => "error", "message" => "Failed"]);
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
    }
}
