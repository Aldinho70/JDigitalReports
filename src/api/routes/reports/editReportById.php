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
$requeridos = ["id", "monitorista", "cliente", "Idunidad", "tipoReporte"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

// Fecha actualizada (si desea mantener el mismo comportamiento)
date_default_timezone_set('America/Monterrey');
$fechaReporte = date('Y-m-d H:i:s');

// Consulta SQL
$sql = "UPDATE `reportes` SET
    fechaReporte = ?, 
    monitorista = ?, 
    cliente = ?, 
    Idunidad = ?, 
    nombreUnidad = ?, 
    tipoReporte = ?, 
    comentario = ?
WHERE id = ?";

try {
    $db->query($sql, [
        $fechaReporte,
        $body["monitorista"],
        $body["cliente"],
        $body["Idunidad"],
        $body["nombreUnidad"] ?? "",
        $body["tipoReporte"],
        $body["comentario"] ?? "",
        $body["id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
