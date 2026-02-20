<?php
require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

header("Content-Type: application/json; charset=utf-8");

$db = new DB();

$filter = $_GET['filter'] ?? 'allReportes';

$where = "";

switch ($filter) {
    case "atendidas":
        $where = "WHERE solucionado = 'si'";
        break;

    case "noAtendidas":
        $where = "WHERE solucionado = 0";
        break;

    case "facturados":
        $where = "WHERE facturacion_tipo = 'servicio'";
        break;

    case "nofacturados":
        $where = "WHERE facturacion_tipo IS NULL OR facturacion_tipo <> 'servicio'";
        break;

    case "allReportes":
    default:
        $where = "";
        break;
}

$sql = "SELECT * FROM vw_reports_tickets $where";
$data = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
