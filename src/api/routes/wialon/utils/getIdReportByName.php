<?php
require_once __DIR__ . "/../../../config/cors.php";
require_once __DIR__ . "/../../../helpers/wialon.helpers.php";

header("Content-Type: application/json; charset=utf-8");

// 1) Leer body
$body = json_decode(file_get_contents("php://input"), true);

$token       = $body["token"]       ?? null;
$accountName = $body["accountName"] ?? null;
$reportName  = $body["reportName"]  ?? null;

if (!$token || !$accountName || !$reportName) {
    echo json_encode([
        "error" => true,
        "message" => "Missing required fields: token, accountName, reportName"
    ]);
    exit;
}

// 2) Obtener SID
$sid = getSid($token);
if (!$sid) {
    echo json_encode(["error" => true, "message" => "Invalid token, could not get SID"]);
    exit;
}

// 3) Buscar SOLO recursos tipo "avl_resource"
$searchParams = [
    "spec" => [
        "itemsType" => "avl_resource",
        "propName" => "sys_name",
        "propValueMask" => "*",
        "sortType" => "sys_name"
    ],
    "force" => 1,
    "flags" => 8193, // incluye rep
    "from" => 0,
    "to" => 0
];

$searchResp = callWialon("core/search_items", $searchParams, $sid);

// Validar recursos
if (!isset($searchResp["items"]) || count($searchResp["items"]) === 0) {
    echo json_encode(["error" => true, "message" => "No resources found"]);
    exit;
}

// 4) Buscar el recurso que coincida con el nombre solicitado
$targetAccount = null;
$idRess = null;
foreach ($searchResp["items"] as $resource) {
    if (strcasecmp($resource["nm"], $accountName) === 0) {
        $targetAccount = $resource;
        $idRess = $resource["id"];
        break;
    }
}

if (!$targetAccount) {
    echo json_encode([
        "error" => true,
        "message" => "Account not found",
        "accounts_found" => array_map(fn($r) => $r["nm"], $searchResp["items"])
    ]);
    exit;
}

// 5) Verificar si ese recurso tiene reportes
if (!isset($targetAccount["rep"]) || count($targetAccount["rep"]) === 0) {
    echo json_encode([
        "error" => true,
        "message" => "The account exists but has no reports (rep is empty)"
    ]);
    exit;
}

// 6) Buscar el reporte exacto
foreach ($targetAccount["rep"] as $report) {
    if (strcasecmp($report["n"], $reportName) === 0) {
        echo json_encode([
            "error" => false,
            "account" => $accountName,
            "resourceId" => $idRess,
            "reportName" => $reportName,
            "templateId" => $report["id"]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 7) Si no se encontró
echo json_encode([
    "error" => true,
    "message" => "Report not found in account",
    "account" => $accountName,
    "reportName" => $reportName
]);
