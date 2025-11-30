<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Validar ID
if (empty($body["id"])) {
    echo json_encode(["error" => "Falta el campo: id"]);
    exit;
}

$id = intval($body["id"]);

$sql = "SELECT * FROM view_tickets_reports WHERE id = ?";

try {
    $data = $db->query($sql, [$id])->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "ok", "mensaje" => $data]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
