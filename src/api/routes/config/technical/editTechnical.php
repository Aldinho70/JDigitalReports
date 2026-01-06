<?php
require "../../../core/db.php";
require_once __DIR__ . "/../../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

$requeridos = ["id", "nombre", "ciudad", "numero_telefono"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

$sql = "UPDATE tecnicos SET
            nombre = ?,
            ciudad = ?,
            numero_telefono = ?
        WHERE id = ?";

try {
    $db->query($sql, [
        $body["nombre"],
        $body["ciudad"],
        $body["numero_telefono"],
        $body["id"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
