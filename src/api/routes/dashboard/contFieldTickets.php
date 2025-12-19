<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$body = json_decode(file_get_contents("php://input"), true);

if (!$body || empty($body["campo"])) {
    echo json_encode(["error" => "Falta el campo a agrupar"]);
    exit;
}

// 🔐 Campos permitidos (WHITELIST)
$camposPermitidos = [
    "cliente",
    "monitorista",
    "tipoReporte",
    "estado"
];

$campo = $body["campo"];

if (!in_array($campo, $camposPermitidos)) {
    echo json_encode(["error" => "Campo no permitido"]);
    exit;
}

// ⚠️ El nombre del campo NO va como parámetro, va directo (ya validado)
$sql = "
    SELECT 
        $campo AS valor,
        COUNT(*) AS total
    FROM reportes
    GROUP BY $campo
";

$data = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
