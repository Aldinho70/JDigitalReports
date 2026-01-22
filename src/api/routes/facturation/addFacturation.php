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
    "fecha_limite_pago"
];
$fechaAsignacion = date('Y-m-d H:i:s');


foreach ($requeridos as $campo) {
    if (!isset($body[$campo]) || $body[$campo] === "") {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        
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
            fecha_pago,
            status_pago
            comentarios_facturacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

try {
    $db->query($sql, [
        $body["folio"] ?? null,
        $body["ticket_id"],
        $body["cliente"],
        $body["tipo_cobro"],
        $body["concepto"],
        $body["costo_cliente"],
        $fechaAsignacion,
        $body["fecha_limite_pago"],
        $body["status_pago"],
        $body["fecha_pago"],
        $body["comentarios_facturacion"] ?? null
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}


// INSERT INTO `cobros_clientes` (`id`,
//  `folio`,
//  `ticket_id`,
//  `cliente`,
//  `tipo_cobro`,
//  `concepto`,
//  `costo_cliente`,
//  `fecha_expedicion`,
//  `fecha_limite_pago`,
//  `fecha_pago`,
//  `status_pago`,
//  `created_at`,
//  `updated_at`,
//  `comentarios_facturacion`) VALUES (NULL,
//  NULL,
//  '',
//  '',
//  'servicio',
//  '',
//  '',
//  current_timestamp(),
//  current_timestamp(),
//  NULL,
//  'pendiente',
//  current_timestamp(),
//  current_timestamp(),
//  'Pendiente de pago')