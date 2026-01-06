<?php
require "../../../core/db.php";
require_once __DIR__ . "/../../../config/cors.php";

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Campos obligatorios
$requeridos = ["nombre", "ciudad", "numero_telefono"];
foreach ($requeridos as $campo) {
    if (empty($body[$campo])) {
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

// Query INSERT
$sql = "INSERT INTO tecnicos (nombre, ciudad, numero_telefono)
        VALUES (?, ?, ?)";

try {
    $db->query($sql, [
        $body["nombre"],
        $body["ciudad"],
        $body["numero_telefono"]
    ]);

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
