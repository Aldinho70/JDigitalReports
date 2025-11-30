<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();
$data = $db->query("SELECT * FROM `view_tickets_reports`")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);


//http://localhost:8080/repos/Jornada%20Digital/JornadaDigital.ReportesDeUnidades.com/src/api/routes/reports/getReports.php