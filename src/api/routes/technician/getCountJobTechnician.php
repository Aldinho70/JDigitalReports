<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

try {
    $data = $db->query("SELECT 
                                VRT.technician_name, 
                                VRT.technician_phone, 
                                VRT.technician_city, 
                                VRT.technician_id, 
                                AVG( VRT.service_cost ) AS service_cost,
                                COUNT(*) AS 'count_assignation'
                            FROM 
                                vw_reports_technicians AS VRT 
                            GROUP BY VRT.technician_id;")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "ok", "mensaje" => $data]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>