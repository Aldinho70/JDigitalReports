<?php
require "../../core/db.php";
require_once __DIR__ . "../../config/cors.php";

$db = new DB();

// Leer body JSON
$body = json_decode(file_get_contents("php://input"), true);

// Validar datos mínimos
if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

$sql = "INSERT INTO `reportes` 
(`fechaReporte`, `monitorista`, `cliente`, `Idunidad`, `nombreUnidad`, `tipoReporte`, `comentario`)
VALUES (?, ?, ?, ?, ?, ?, ?)";

date_default_timezone_set('America/Monterrey');
$fechaReporte = date('Y-m-d H:i:s');

$db->query($sql, [
    $fechaReporte,
    $body["monitorista"] ?? null,
    $body["cliente"] ?? null,
    $body["Idunidad"] ?? null,
    $body["nombreUnidad"] ?? "",
    $body["tipoReporte"] ?? "",
    $body["comentario"] ?? ""
]);

echo json_encode(["status" => "ok"]);
