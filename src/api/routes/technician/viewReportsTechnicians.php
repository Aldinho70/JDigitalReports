<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

try {
    $data = $db->query("SELECT * FROM `vw_reports_technicians`")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "ok", "mensaje" => $data]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>