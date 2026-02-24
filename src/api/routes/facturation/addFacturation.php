<?php
require "../../core/db.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

$requeridos = [ "report_id" ];
$fechaAsignacion = date('Y-m-d H:i:s');

foreach ($requeridos as $campo) {
    if (!isset($body[$campo]) || $body[$campo] === "") {
        echo json_encode(["error" => "Falta el campo: $campo"]);
    }
}

$sql = "INSERT INTO client_charges (
            report_id,
            is_billable,
            invoice_folio,
            amount,
            concept,
            payment_status,
            due_date,
            paid_at,
            comment
        ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ? )";

try {
    $db->query($sql, [
        $body["report_id"] ?? null,
        $body["is_billable"],
        $body["invoice_folio"],
        $body["amount"],
        $body["concept"],
        $body["payment_status"],
        $fechaAsignacion,
        $body["paid_at"],
        $body["comment"],
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}