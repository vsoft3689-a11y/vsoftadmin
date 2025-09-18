<?php
header("Content-Type: application/json");

require __DIR__ . '/../vendor/autoload.php';
require_once "../config/database.php";
require_once "../model/ProjectModel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

$db = (new Database())->connect();
$projectModel = new ProjectModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];
    $fileName = $_FILES['excel_file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, ['xls', 'xlsx'])) {
        echo json_encode(["status" => "error", "message" => "Invalid file type. Upload Excel only."]);
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $updatedTitles = [];
        $insertedTitles = [];
        $skippedTitles = [];

        foreach ($rows as $index => $row) {
            if ($index == 1) continue; // skip header row

            $title = trim($row['E'] ?? "");
            if (empty($title)) {
                $skipped++;
                $skippedTitles[] = "Row $index (No Title)";
                continue;
            }

            $data = [
                'degree'              => $row['A'] ?? null,
                'branch'              => $row['B'] ?? null,
                'type'                => $row['C'] ?? null,
                'domain'              => $row['D'] ?? null,
                'title'               => $title,
                'description'         => $row['F'] ?? null,
                'technologies'        => $row['G'] ?? null,
                'price'               => $row['H'] ?? null,
                'youtube_url'         => $row['I'] ?? null,
                'file_path_abstract'  => $row['J'] ?? null,
                'file_path_basepaper' => $row['K'] ?? null,
            ];

            $exists = $projectModel->existsByTitle($title);

            if ($exists) {
                if ($projectModel->updateByTitle($title, $data)) {
                    $updated++;
                    $updatedTitles[] = $title;
                } else {
                    $skipped++;
                    $skippedTitles[] = $title;
                }
            } else {
                if ($projectModel->create($data)) {
                    $inserted++;
                    $insertedTitles[] = $title;
                } else {
                    $skipped++;
                    $skippedTitles[] = $title;
                }
            }
        }

        echo json_encode([
            "status"   => "success",
            "message"  => "Upload completed",
            "inserted" => $inserted,
            "updated"  => $updated,
            "skipped"  => $skipped
        ]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
}
