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

$requeridos = ["monitorista", "cliente", "Idunidad", "tipoReporte"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

date_default_timezone_set('America/Monterrey');
$fechaReporte = date('Y-m-d H:i:s');

$sql = "INSERT INTO `reportes`
(`fechaReporte`, `monitorista`, `cliente`, `Idunidad`, `nombreUnidad`, `tipoReporte`, `comentario`)
VALUES (?, ?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $fechaReporte,
        $body["monitorista"],
        $body["cliente"],
        $body["Idunidad"],
        $body["nombreUnidad"] ?? "",
        $body["tipoReporte"],
        $body["comentario"] ?? ""
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
