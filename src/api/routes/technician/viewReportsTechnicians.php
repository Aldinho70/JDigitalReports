<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

// Consulta dinámica segura
$data = $db->query("SELECT * FROM `vw_reports_technicians`")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>

<!-- SELECT 	
	RA.id AS 'assignation_id',
    RA.report_id,
    T.id AS 'technician_id',
    T.name  AS 'technician_name',
    T.city AS 'technician_city',
    T.phone AS 'technician_phone',
    RA.service_cost,
    RA.comment
FROM 
	`report_assignments` AS RA
INNER JOIN technicians AS T
ON T.id = RA.technician_id; -->
