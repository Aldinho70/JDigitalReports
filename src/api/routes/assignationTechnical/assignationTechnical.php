<?php
require "../../core/db.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Campos obligatorios
$requeridos = ["ticket_id", "tecnico_id"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

// Valores por defecto
date_default_timezone_set('America/Monterrey');
$fechaAsignacion = date('Y-m-d H:i:s');
$status = "pendiente";

// Query INSERT
$sql = "INSERT INTO asignaciones_tecnicos (
            ticket_id,
            tecnico_id,
            unidad,
            cliente,
            fecha_asignacion,
            fecha_estimada_fin,
            costo_tecnico,
            costo_cliente,
            status,
            comentarios
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $body["ticket_id"],
        $body["tecnico_id"],
        $body["unidad"] ?? null,
        $body["cliente"] ?? null,
        $fechaAsignacion,
        $body["fecha_estimada_fin"] ?? null,
        $body["costo_tecnico"] ?? null,
        $body["costo_cliente"] ?? null,
        $status,
        $body["comentarios"] ?? null
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
