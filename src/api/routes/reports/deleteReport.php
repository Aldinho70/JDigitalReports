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

$sql = "DELETE FROM reportes WHERE id = ?";

try {
    $db->query($sql, [$id]);

    echo json_encode(["status" => "ok", "mensaje" => "Registro eliminado"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
