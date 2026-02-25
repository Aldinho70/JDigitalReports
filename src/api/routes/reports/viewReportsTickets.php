<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$filter = $_GET['filter'] ?? 'allReportes';

$where = "";

switch ($filter) {
    case "completed":
        $where = "WHERE status_ticket = 'completed'";
        break;

    case "pending":
        $where = "WHERE status_ticket = 'pending'";
        break;

    case "isBillable":
        $where = "WHERE is_billable = 1";
        break;

    case "noBillable":
        $where = "WHERE is_billable = 0 OR is_billable IS NULL";
        break;

    case "allReportes":
    default:
        $where = "";
        break;
}

$sql = "SELECT * FROM vw_reports_tickets $where";
$data = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
