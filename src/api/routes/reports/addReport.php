<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

$requeridos = ["client_name", "report_type", "unit_name", "monitor_id"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

date_default_timezone_set('America/Monterrey');
$fechaReporte = date('Y-m-d H:i:s');

$sql = "INSERT INTO `reports` ( `report_date`, `monitor_id`, `client_name`, `unit_name`, `report_type`, `comment` ) 
        VALUES ( ?, ?, ?, ?, ?, ? )";

try {
    $db->query($sql, [
        $fechaReporte,
        $body["monitor_id"],
        $body["client_name"],
        $body["unit_name"],
        $body["report_type"] ?? "",
        $body["comment"],
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
