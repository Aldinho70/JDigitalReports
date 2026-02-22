<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Validar campos obligatorios
$requeridos = [ "report_id" ];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
    }
}

// Consulta SQL corregida
$sql = "UPDATE 
            `tickets` 
        SET
            status = ?, 
            resolution_type = ?, 
            assigned_to_technician = ?, 
            is_billable = ?,
            comment = ?
        WHERE 
            report_id = ?";

try {
    $db->query($sql, [
        $body["status"],
        $body["resolution_type"],
        $body["assigned_to_technician"],
        $body["is_billable"],
        $body["comment"],
        $body["report_id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}