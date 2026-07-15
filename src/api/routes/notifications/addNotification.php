<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON invalido"]);
    exit;
}

// Consulta SQL corregida
$sql = "INSERT INTO `notifications` (`id`, `unit_id`, `notification_name`, `notification_description`, `longitud`, `latitud`, `color`) 
        VALUES (null, ?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $body["unit_id"],
        $body["notification_name"],
        $body["notification_description"],
        $body["longitud"],
        $body["latitud"],
        $body["color"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
