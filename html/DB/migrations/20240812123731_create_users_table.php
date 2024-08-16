<?php
namespace DB\Migrations;

use Libs\DB\Migration\Migration;

class Create_users_table extends Migration {
    private $pdo;

    public function __construct(
        $pdo
    )
    {
        $this->pdo = $pdo;
    }

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `department_id` INT NOT NULL,
            `rank` INT NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function down() {
        $this->pdo->exec("DROP TABLE IF EXISTS users;");
    }
}
