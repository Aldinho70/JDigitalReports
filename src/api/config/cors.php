<?php
// === CONFIG CORS GLOBAL ===

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos GET y POST
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir headers personalizados si los necesitas
header("Access-Control-Allow-Headers: Content-Type");

// Si es OPTIONS, responder vacío y terminar (evita errores preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


