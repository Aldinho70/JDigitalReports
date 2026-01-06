<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Validar que la query venga en el body
if (empty($body["query"])) {
    echo json_encode(["error" => "Falta el campo: query"]);
    exit;
}

$query = $body["query"];

try {
    $data = $db->query( $query, [] )->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "ok", "mensaje" => $data]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
