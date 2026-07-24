<?php
require "../../../core/db.php";
require_once __DIR__ . "/../../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$sql = "
    DELETE FROM notifications
    WHERE `date` < NOW() - INTERVAL 15 DAY;
";

try {
    $stmt = $db->query($sql);

    echo json_encode([
        "success" => true,
        "message" => "Notificaciones antiguas eliminadas.",
        "rows_deleted" => $stmt->rowCount()
    ]);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}