<?php
require_once __DIR__ . "/../../config/cors.php";
header('Content-Type: application/json');

$servername = "208.109.27.194";
$username   = "super";
$password   = "jornadadigital_2024";
$dbname     = "fullcalendar";

$conn = new mysqli($servername, $username, $password, $dbname);

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    echo json_encode(["error" => "JSON invalido"]);
    exit;
}

// Validar que la query venga en el body
if (empty($body["query"])) {
    echo json_encode(["error" => "Falta el campo: query"]);
    exit;
}

$query = $body["query"];

try {
    $result = $conn->query($query);

    if ($result === false) {
        echo json_encode(["error" => $conn->error]);
        exit;
    }

    if ($result instanceof mysqli_result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(["status" => "ok", "mensaje" => $data]);
    } else {
        echo json_encode([
            "status" => "ok",
            "mensaje" => ["affected_rows" => $conn->affected_rows]
        ]);
    }
} catch (\Throwable $th) {
    echo json_encode(["error" => $th->getMessage()]);
}

$conn->close();
?>