<?php
require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

// Capturar token por GET
$token = $_GET['token'] ?? null;

if (!$token) {
    echo json_encode(["error" => "Token no recibido"]);
    exit;
}

// Pasar el token a tu helper (si aplica)
$sid = getSid($token);

if (!$sid) {
    echo json_encode(["error" => "No se pudo obtener el SID"]);
    exit;
}

echo json_encode([
    "status" => "success",
    "sid" => $sid
]);
