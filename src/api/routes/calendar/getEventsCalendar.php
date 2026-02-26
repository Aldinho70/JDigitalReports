<?php

require_once __DIR__ . "/../../config/cors.php";
// Datos de conexión
$servername = "208.109.27.194";
$username   = "super";
$password   = "jornadadigital_2024";
$dbname     = "fullcalendar";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

$filter = $_GET['filter'] ?? '';

if ($filter) {
    $stmt = $conn->prepare("SELECT * FROM data WHERE title = ?");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM data");
}

// Consulta
// $result = $conn->query($sql);

// Arreglo para guardar los datos
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Cerrar conexión
$conn->close();

// Regresar datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
