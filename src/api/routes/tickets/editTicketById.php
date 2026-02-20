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
$requeridos = ["id", "comentario_soporte", "accion", "solucionado", "resolucion", "estado"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        
    }
}

// Consulta SQL corregida
$sql = "UPDATE `tickets_soporte` SET
    comentario_soporte = ?, 
    accion = ?, 
    solucionado = ?, 
    resolucion = ?,
    estado = ?
WHERE id_ticket = ?";

// INSERT INTO `tickets` (`report_id`, `status`, `resolution_type`, `assigned_to_technician`, `is_billable`, `created_at`, `updated_at`) 
//                VALUES ('', 'pending', NULL, '0', '0', current_timestamp(), current_timestamp())

try {
    $db->query($sql, [
        $body["comentario_soporte"],
        $body["accion"],
        $body["solucionado"],
        $body["resolucion"],
        $body["estado"],
        $body["id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
