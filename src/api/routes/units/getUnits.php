<?php
require_once __DIR__ . "/../../config/cors.php";
require_once __DIR__ . "/../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

// 1. Obtener SID válido
$sid = getSid();

if (!$sid) {
    echo json_encode(["error" => "No se pudo obtener el SID"]);
    exit;
}

// 2. Parámetros de búsqueda
$params = [
    "spec" => [
        "itemsType" => "avl_unit",
        "propName" => "sys_name",
        "propValueMask" => "*",
        "sortType" => "sys_name"
    ],
    "force" => 1,
    "flags" => 17,
    "from" => 0,
    "to" => 0
];

// 3. Llamada principal
$response = callWialon("core/search_items", $params, $sid);

// Validación
if (!isset($response["items"])) {
    echo json_encode([
        "error" => "No se pudieron obtener unidades",
        "response" => $response
    ]);
    exit;
}

$units = [];

// 4. Procesar unidades
foreach ($response["items"] as $item) {
    $units[] = [
        "id" => $item["id"] ?? null,
        "nombre" => $item["nm"] ?? null,
        "icono" => $item["uri"] ?? null,
    ];
}

echo json_encode([
    "status" => "success",
    "sid" => $sid,
    "unidades" => $units,
    "total" => count($units)
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
