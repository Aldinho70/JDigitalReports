<?php
// require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../../config/cors.php";
require_once __DIR__ . "/../../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

// ===============================
// 1. Leer body JSON
// ===============================
$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "El body no contiene JSON válido"]);
    exit;
}

$token       = $body["token"]       ?? "733a7307cd0dd55c139f57fcaa9269d33033EF2588751D51ECB53AA291A5B6501EF5426B";
$resourceId  = $body["resourceId"]  ?? 28675002;
$templateId  = $body["templateId"]  ?? 1;
$objectId    = $body["objectId"]    ?? 29566197; //GAFI ABA VTAS GMZ
$fromParam   = $body["from"]        ?? '2025-11-01 00:00:00';
$toParam     = $body["to"]          ?? '2025-12-01 00:00:00';

if (!$token || !$resourceId || !$templateId || !$objectId || !$fromParam || !$toParam) {
    echo json_encode([
        "error" => "Faltan parámetros. Requerido: token, resourceId, templateId, objectId, from, to"
    ]);
    exit;
}

// Convertir fechas a timestamp
$from = strtotime($fromParam);
$to   = strtotime($toParam);

if (!$from || !$to) {
    echo json_encode(["error" => "Formato de fecha inválido"]);
    exit;
}

// ===============================
// 2. Obtener SID
// ===============================
$sid = getSid($token);

if (!$sid) {
    echo json_encode(["error" => "No se pudo obtener el SID"]);
    exit;
}

// ===============================
// 3. Ejecutar reporte
// ===============================
$execParams = [
    "reportResourceId" => intval($resourceId),
    "reportTemplateId" => intval($templateId),
    "reportObjectId" => intval($objectId),
    "reportObjectSecId" => 0,
    "interval" => [
        "from" => $from,
        "to" => $to,
        "flags" => 0
    ]
];

$execResponse = callWialon("report/exec_report", $execParams, $sid);

if (!isset($execResponse["reportResult"]["tables"])) {
    echo json_encode([
        "error" => "El reporte no devolvió tablas",
        "response" => $execResponse
    ]);
    exit;
}

// ===============================
// 4. Obtener todas las tablas y filas
// ===============================
$tables = $execResponse["reportResult"]["tables"];
$tablesReturn = [];

foreach ($tables as $index => $tbl) {

    $totalRows = $tbl["rows"] ?? 0;

    if ($totalRows > 0) {
        $rowsResponse = callWialon("report/get_result_rows", [
            "tableIndex" => $index,
            "indexFrom" => 0,
            "indexTo" => $totalRows - 1
        ], $sid);
    } else {
        $rowsResponse = [];
    }

    $tablesReturn[] = [
        "table_index" => $index,
        "table_name" => $tbl["name"] ?? "unknown",
        "rows_count" => $totalRows,
        "rows" => $rowsResponse
    ];
}

// ===============================
// 5. Respuesta final
// ===============================
echo json_encode([
    "status" => "success",
    "sid" => $sid,
    "resourceId" => $resourceId,
    "templateId" => $templateId,
    "objectId" => $objectId,
    "interval" => [
        "from" => $from,
        "to" => $to
    ],
    "tables" => $tablesReturn
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
