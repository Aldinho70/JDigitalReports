<?php
require "../../core/db.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body || empty($body["id"])) {
    echo json_encode(["error" => "ID requerido"]);
    exit;
}

$sql = "UPDATE cobros_clientes 
        SET status_pago = 'cancelado'
        WHERE id = ?";

try {
    $db->query($sql, [$body["id"]]);
    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
