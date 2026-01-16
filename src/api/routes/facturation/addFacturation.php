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

$requeridos = [
    "ticket_id",
    "cliente",
    "tipo_cobro",
    "concepto",
    "costo_cliente",
    "fecha_expedicion",
    "fecha_limite_pago"
];

foreach ($requeridos as $campo) {
    if (!isset($body[$campo]) || $body[$campo] === "") {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

$sql = "INSERT INTO cobros_clientes (
            folio,
            ticket_id,
            cliente,
            tipo_cobro,
            concepto,
            costo_cliente,
            fecha_expedicion,
            fecha_limite_pago,
            comentarios_facturacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $body["folio"] ?? null,
        $body["ticket_id"],
        $body["cliente"],
        $body["tipo_cobro"],
        $body["concepto"],
        $body["costo_cliente"],
        $body["fecha_expedicion"],
        $body["fecha_limite_pago"],
        $body["comentarios_facturacion"] ?? null
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
