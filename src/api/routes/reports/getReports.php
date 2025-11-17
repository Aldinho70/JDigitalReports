<?php
require "../../core/db.php";

$db = new DB();
$data = $db->query("SELECT * FROM reportes")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);


//http://localhost:8080/repos/Jornada%20Digital/JornadaDigital.ReportesDeUnidades.com/src/api/routes/reports/getReports.php