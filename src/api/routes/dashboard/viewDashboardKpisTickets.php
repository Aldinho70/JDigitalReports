<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

// Consulta dinámica segura
$data = $db->query("SELECT * FROM `view_dashboard_kpis_tickets`")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
