<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON invalido"]);
    exit;
}

// Validar campos obligatorios
$requeridos = ["report_id"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
    }
}

// Consulta SQL corregida
$sql = "INSERT INTO `tickets` (`report_id`, `status`, `resolution_type`, `assigned_to_technician`, `is_billable`, `comment`) 
        VALUES (?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $body["report_id"],
        $body["status"],
        $body["resolution_type"],
        $body["assigned_to_technician"],
        $body["is_billable"],
        $body["comment"],
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
