<?php
require "../../../core/db.php";
require_once __DIR__ . "/../../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (empty($body["id"])) {
    echo json_encode(["error" => "Falta el campo: id"]);
    exit;
}

$sql = "DELETE FROM technicians WHERE id = ?";

try {
    $db->query($sql, [$body["id"]]);
    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
