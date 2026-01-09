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
$requeridos = ["id_asignacion", "tecnico_id", "fecha_estimada_fin", "costo_tecnico", "costo_cliente", "status"];
foreach ($requeridos as $campo) {
    if (!isset($body[$campo]) || $body[$campo] === "") {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

// Query UPDATE
$sql = "UPDATE asignaciones_tecnicos SET
            tecnico_id = ?,
            fecha_estimada_fin = ?,
            costo_tecnico = ?,
            costo_cliente = ?,
            status = ?,
            comentarios = ?
        WHERE id_asignacion = ?";

try {
    $db->query($sql, [
        $body["tecnico_id"],
        $body["fecha_estimada_fin"],
        $body["costo_tecnico"],
        $body["costo_cliente"],
        $body["status"],
        $body["comentarios"] ?? null,
        $body["id_asignacion"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
