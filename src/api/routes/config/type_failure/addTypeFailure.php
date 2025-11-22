<?php
require "../../../core/db.php";
require_once __DIR__ . "/../../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Campos requeridos
$requeridos = ["nombre_falla", "categoria", "criticidad"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

$sql = "INSERT INTO fallas_unidad (nombre_falla, categoria, criticidad)
        VALUES (?, ?, ?)";

try {
    $db->query($sql, [
        $body["nombre_falla"],
        $body["categoria"],
        $body["criticidad"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}