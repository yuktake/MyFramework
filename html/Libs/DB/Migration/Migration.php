<?php
namespace Libs\DB\Migration;

class Migration {
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function up() {
        throw new \NotImplementedException();
    }

    public function down() {
        throw new \NotImplementedException();
    }
}