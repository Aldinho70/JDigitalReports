<?php
// === CONFIG CORS GLOBAL ===

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir métodos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir headers personalizados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Si es OPTIONS, responder vacío y terminar (evita errores preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
