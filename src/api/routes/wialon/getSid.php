<?php
require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

$sid = getSid();

if (!$sid) {
    echo json_encode(["error" => "No se pudo obtener el SID"]);
    exit;
}

echo json_encode([
    "status" => "success",
    "sid" => $sid
]);
