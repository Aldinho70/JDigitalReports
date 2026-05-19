<?php

require_once __DIR__ . "/../../../config/cors.php";
require_once __DIR__ . "/../../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

// ===============================
// BODY
// ===============================
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {

    http_response_code(400);

    echo json_encode([
        "error" => "JSON inválido"
    ]);

    exit;
}

// ===============================
// PARAMS
// ===============================
$token = $body["token"] ?? null;
$unitId = $body["unitId"] ?? null;
$dateFrom = $body["dateFrom"] ?? null;
$dateTo = $body["dateTo"] ?? null;

// ===============================
// VALIDACIONES
// ===============================
if (
    empty($token) ||
    empty($unitId) ||
    empty($dateFrom) ||
    empty($dateTo)
) {

    http_response_code(400);

    echo json_encode([
        "error" => "Faltan parámetros"
    ]);

    exit;
}

// ===============================
// SID
// ===============================
$sid = getSid($token);

if (!$sid || is_array($sid)) {

    http_response_code(500);

    echo json_encode([
        "error" => "No se pudo obtener SID",
        "detail" => $sid
    ]);

    exit;
}

// ===============================
// HISTORIAL
// ===============================
$history = getUnitHistory(
    $sid,
    $unitId,
    $dateFrom,
    $dateTo
);

// ===============================
// RESPONSE
// ===============================
echo json_encode($history);