<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

if (empty($body["id_report"])) {
    echo json_encode(["error" => "Falta el campo: id del reporte"]);
    exit;
}

$id = intval($body["id_report"]);

try {

    // Iniciar transacción
    $db->query("START TRANSACTION");

    // 1️⃣ Eliminar tablas hijas (orden importa)
    $db->query("DELETE FROM report_assignments WHERE report_id = ?", [$id]);
    $db->query("DELETE FROM tickets WHERE report_id = ?", [$id]);

    // 2️⃣ Eliminar padre
    $db->query("DELETE FROM reports WHERE id = ?", [$id]);

    // Confirmar cambios
    $db->query("COMMIT");

    echo json_encode(["status" => "ok", "mensaje" => "Reporte y relaciones eliminadas correctamente"]);

} catch (Exception $e) {

    // Revertir si algo falla
    $db->query("ROLLBACK");

    echo json_encode(["error" => $e->getMessage()]);
}