<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON invalido"]);
    exit;
}

$sql = "INSERT INTO notification_test (id, notification)
        VALUES (NULL, ?)";

try {

    $notification = json_encode(
        $body,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    $db->query($sql, [$notification]);

    echo json_encode([
        "status" => "ok"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}