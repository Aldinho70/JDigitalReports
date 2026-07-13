<?php

require "../../core/db.php";
require_once __DIR__ . "/../../config/cors.php";

$db = new DB();

try {

    // Payload crudo recibido
    $rawBody = file_get_contents("php://input");

    // Si viene como POST normal
    $postData = $_POST;

    // Intentar convertir a JSON
    $jsonData = json_decode($rawBody, true);

    // Construir objeto completo para almacenar
    $notificationData = [
        "datetime" => date("Y-m-d H:i:s"),
        "post" => $postData,
        "json" => $jsonData,
        "raw" => $rawBody
    ];

    $notification = json_encode(
        $notificationData,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    // Log para depuración
    file_put_contents(
        __DIR__ . "/wialon.log",
        "\n=====================================\n" .
        date("Y-m-d H:i:s") . "\n" .
        $notification . "\n",
        FILE_APPEND
    );

    $sql = "
        INSERT INTO notification_test (
            id,
            notification
        )
        VALUES (
            NULL,
            ?
        )
    ";

    $db->query($sql, [$notification]);

    echo json_encode([
        "status" => "ok"
    ]);

} catch (Exception $e) {

    file_put_contents(
        __DIR__ . "/wialon_error.log",
        date("Y-m-d H:i:s") . " => " . $e->getMessage() . "\n",
        FILE_APPEND
    );

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}