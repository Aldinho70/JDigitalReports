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
$requeridos = ["report_id", "technician_id"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
    }
}

// Valores por defecto
date_default_timezone_set('America/Monterrey');
$fechaAsignacion = date('Y-m-d H:i:s');

// Query INSERT
$sql = "INSERT INTO report_assignments (
            `report_id`,
            `technician_id`,
            `service_cost`,
            `payment_status`,
            `assigned_at`,
            `completed_at`,
            `satus`,
            `comment`) 
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )";

try {
    $db->query($sql, [
        $body["report_id"],
        $body["technician_id"],
        $body["service_cost"] ?? null,
        $body["payment_status"] ?? null,
        $fechaAsignacion,
        $body["completed_at"] ?? null,
        $body["satus"] ?? null,
        $body["comment"] ?? null,
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}