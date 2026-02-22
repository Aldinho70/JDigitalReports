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
if (empty($body["id_report"])) {
    echo json_encode(["error" => "Falta el campo: id del reporte"]);
    exit;
}

$id = intval($body["id_report"]);

$sql = "SELECT * FROM vw_reports_tickets WHERE id_report = ?";

try {
    $data = $db->query($sql, [$id])->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "ok", "mensaje" => $data]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
