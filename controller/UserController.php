<?php
header("Content-Type: application/json");

include __DIR__ . "/../config/database.php";
include __DIR__ . "/../model/UserModel.php";

$action = $_REQUEST['action'] ?? '';

$controller = new UserController();
$controller->handleRequest($action);

class UserController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        $this->userModel = new UserModel($this->db);
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $data = [
                    "name"     => $_POST['name'],
                    "email"    => $_POST['email'],
                    "password" => $_POST['password'],
                    "phone"    => $_POST['phone'] ?? null,
                    "college"  => $_POST['college'] ?? null,
                    "branch"   => $_POST['branch'] ?? null,
                    "year"     => $_POST['year'] ?? null
                ];
                echo json_encode(
                    $this->userModel->create($data) ?
                        ["success" => true, "message" => "Student added"] :
                        ["success" => false, "message" => "Error adding student"]
                );
                break;

            case "read":
                echo json_encode($this->userModel->read());
                break;

            case "update":
                $data = [
                    "id"      => $_POST['id'],
                    "name"    => $_POST['name'],
                    "email"   => $_POST['email'],
                    "phone"   => $_POST['phone'] ?? null,
                    "college" => $_POST['college'] ?? null,
                    "branch"  => $_POST['branch'] ?? null,
                    "year"    => $_POST['year'] ?? null
                ];
                echo json_encode(
                    $this->userModel->update($data) ?
                        ["success" => true, "message" => "Student updated"] :
                        ["success" => false, "message" => "Update failed"]
                );
                break;

            case "delete":
                echo json_encode(
                    $this->userModel->delete($_POST['id']) ?
                        ["success" => true, "message" => "Student deleted"] :
                        ["success" => false, "message" => "Delete failed"]
                );
                break;

            case "toggle_status":
                echo json_encode(
                    $this->userModel->toggleStatus($_POST['id'], $_POST['status']) ?
                        ["success" => true, "message" => "Account status updated"] :
                        ["success" => false, "message" => "Status update failed"]
                );
                break;

            case "verify_email":
                echo json_encode(
                    $this->userModel->verifyEmail($_POST['id']) ?
                        ["success" => true, "message" => "Email verified"] :
                        ["success" => false, "message" => "Failed to verify email"]
                );
                break;

            case "update_password":
                echo json_encode(
                    $this->userModel->updatePassword($_POST['id'], $_POST['password']) ?
                        ["success" => true, "message" => "Password updated"] :
                        ["success" => false, "message" => "Failed to update password"]
                );
                break;

            default:
                echo json_encode(["success" => false, "message" => "Invalid action"]);
        }
    }
}
