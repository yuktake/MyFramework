<?php
namespace Libs\DB\Transaction;

use PDO;

class Transaction {

    protected $pdo;

    public function __construct(string $env="development") {
        $this->pdo = new \PDO(
            "mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8", 
            DB_USER, 
            DB_PASS, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function startTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollback() {
        return $this->pdo->rollback();
    }

    public function __destruct() {
        $this->pdo = null;
    }
}

?>