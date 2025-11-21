<?php
require "../../core/db.php";
// require_once __DIR__ . "../../config/cors.php";

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET y POST
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir headers personalizados si los necesitas
header("Access-Control-Allow-Headers: Content-Type");

$db = new DB();

// Parámetro recibido
$type = $_GET["type"] ?? null;

// Mapeo de vistas permitidas
$views = [
    "status"      => "view_status_tickets",
    "monitorista" => "view_tickets_monitorista",
    "revision"    => "vw_revision"
];

// Validación
if (!$type || !isset($views[$type])) {
    echo json_encode(["error" => "Tipo de vista no válido"]);
    exit;
}

$view = $views[$type];

// Consulta dinámica segura
$data = $db->query("SELECT * FROM `$view`")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
