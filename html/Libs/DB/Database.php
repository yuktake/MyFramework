<?php
namespace Libs\DB;

use PDO;

class Database {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO(
            "mysql:host=" . DB_HOST, 
            DB_USER, 
            DB_PASS, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    public function createDatabase() {
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    }
}