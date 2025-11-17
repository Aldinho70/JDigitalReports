<?php
class DB {
    private $pdo;

    public function __construct() {
        $cfg = require __DIR__ . "/../config/database.php";

        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['db']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $cfg["user"], $cfg["pass"], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    // Consulta general
    public function query($sql, $params = []) {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);
        return $stm;
    }
}
