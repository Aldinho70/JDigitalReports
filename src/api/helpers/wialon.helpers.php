<?php
// require_once __DIR__ . "../config/config.php";

require_once __DIR__ . "/../config/cors.php";

$config = require __DIR__ . "/../config/config.php";

/* ==========================================================
   UTILERÍA GENERAL WIALON
   ========================================================== */

/** Llamada genérica a Wialon */
function callWialon($svc, $params = [], $sid = null) {
    global $config;

    $url = $config["wialon_url"] . "?svc=" . $svc;
    if ($sid) $url .= "&sid=" . $sid;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ["params" => json_encode($params)]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

/** Cargar SID cacheado */
function loadCachedSid() {
    global $config;

    if (!file_exists($config["sid_cache_file"])) {
        return null;
    }

    $data = json_decode(file_get_contents($config["sid_cache_file"]), true);

    // Validar formato
    if (!isset($data["sid"]) || !isset($data["expires"])) {
        return null;
    }

    // Expiró
    if (time() > $data["expires"]) {
        return null;
    }

    return $data["sid"];
}

/** Guardar SID nuevo */
function saveSid($sid) {
    global $config;
    $data = [
        "sid" => $sid,
        "expires" => time() + 540  // 9 minutos (Wialon expira aprox. en 10)
    ];
    file_put_contents($config["sid_cache_file"], json_encode($data));
}

/** Verificar si un SID es válido con "core/ping" */
function testSid($sid) {
    $response = callWialon("core/ping", [], $sid);
    return isset($response["tm"]); // si tm existe, el SID está vivo
}

/** Obtener SID válido (cache o login) */
function getSid( $token ) {get
    // global $config;

    // 1. Intentar usar cache
    $sid = loadCachedSid();
    if ($sid && testSid($sid)) {
        return $sid;
    }

    // 2. Si no sirve, hacer login nuevo
    $login = callWialon("token/login", ["token" => $token]);

    if (!isset($login["eid"])) {
        return $login; // error
    }

    $sid = $login["eid"];

    // Guardar cache
    saveSid($sid);

    return $sid;
}