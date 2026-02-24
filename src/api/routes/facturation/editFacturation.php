<?php
require "../../core/db.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body || empty($body["id_payment"])) {
    echo json_encode(["error" => "Id de factura requerido"]);
}

$sql = "UPDATE client_charges SET
            is_billable = ?,
            invoice_folio = ?,
            amount = ?,
            concept = ?,
            payment_status = ?,
            paid_at = ?,
            comment = ?
        WHERE id = ?";

try {
    $db->query($sql, [
        $body["is_billable"] ?? null,
        $body["invoice_folio"] ?? null,
        $body["amount"] ?? null,
        $body["concept"] ?? null,
        $body["payment_status"] ?? null,
        $body["paid_at"] ?? null,
        $body["comment"] ?? "pendiente",
        $body["id_payment"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
