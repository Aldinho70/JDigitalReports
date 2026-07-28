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
$sql = "INSERT INTO `notification_follow_up` ( `notification_id`, `monitorist`, `comment`, `resolution`) 
        VALUES ( ?, ?, ?, ? )";

try {
    $db->query($sql, [
        $body["notification_id"],
        $body["monitorist"],
        $body["comment"],
        $body["resolution"],
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
