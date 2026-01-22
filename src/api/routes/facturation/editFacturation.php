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
    
}

$sql = "UPDATE cobros_clientes SET
            folio = ?,
            tipo_cobro = ?,
            concepto = ?,
            costo_cliente = ?,
            fecha_limite_pago = ?,
            fecha_pago = ?,
            status_pago = ?,
            comentarios_facturacion = ?
        WHERE id = ?";

try {
    $db->query($sql, [
        $body["folio"] ?? null,
        $body["tipo_cobro"] ?? null,
        $body["concepto"] ?? null,
        $body["costo_cliente"] ?? null,
        $body["fecha_limite_pago"] ?? null,
        $body["fecha_pago"] ?? null,
        $body["status_pago"] ?? "pendiente",
        $body["comentarios_facturacion"] ?? null,
        $body["id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
