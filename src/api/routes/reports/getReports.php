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
$data = $db->query("SELECT * FROM reportes")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);


//http://localhost:8080/repos/Jornada%20Digital/JornadaDigital.ReportesDeUnidades.com/src/api/routes/reports/getReports.php