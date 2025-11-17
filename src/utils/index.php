<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// URL: api/?endpoint=usuarios/get
$endpoint = $_GET["endpoint"] ?? null;
$path = __DIR__ . "/routes/" . $endpoint . ".php";

if (file_exists($path)) {
    require $path;
} else {
    echo json_encode(["error" => "Endpoint no encontrado"]);
}
