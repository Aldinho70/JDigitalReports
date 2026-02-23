<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Campos obligatorios
$requeridos = ["assignation_id"];
foreach ($requeridos as $campo) {
    if (!isset($body[$campo]) || $body[$campo] === "") {
        echo json_encode(["error" => "Falta el campo: $campo"]);
    
    }
}

// Query UPDATE
$sql = "UPDATE 
            `report_assignments` 
        SET 
            `technician_id` = ?,
            `service_cost` = ?,
            `payment_status` = ?,
            `completed_at` = ?,
            `comment` = ?,
            `satus` = ?
        WHERE 
            `report_assignments`.`id` = ?;";

try {
    $db->query($sql, [
        $body["technician_id"],
        $body["service_cost"],
        $body["payment_status"],
        $body["completed_at"],
        $body["comment"],
        $body["satus"],
        $body["assignation_id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
