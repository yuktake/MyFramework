<?php
namespace Libs\DB\Migration;

use PDO;

class MigrationManager {
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function createMigrationsTable(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function migrate()
    {
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];

        $files = scandir(__DIR__ . '/../../../DB/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once __DIR__ . '/../../../DB/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            preg_match('/^(\d{14})_(.*)$/', $className, $matches);
            $className = "\\DB\\Migrations\\".ucwords($matches[2]);
            $instance = new $className($this->pdo);
            $instance->up();

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
            echo "Migrations applied: " . implode(", ", $newMigrations) . "\n";
        } else {
            echo "All migrations are applied.\n";
        }
    }

    public function rollback()
    {
        $appliedMigrations = $this->getAppliedMigrations();
        $rollbackMigrations = array_pop($appliedMigrations);

        if (!$rollbackMigrations) {
            echo "No migrations to rollback.\n";
            return;
        }

        require __DIR__ . '/../../../DB/migrations/' . $rollbackMigrations;
        $className = pathinfo($rollbackMigrations, PATHINFO_FILENAME);
        preg_match('/^(\d{14})_(.*)$/', $className, $matches);
        $className = "\\DB\\Migrations\\".ucwords($matches[2]);
        $instance = new $className($this->pdo);
        $instance->down();

        $this->pdo->exec("DELETE FROM migrations WHERE migration = '$rollbackMigrations'");
        echo "Migration rolled back: $rollbackMigrations\n";
    }

    private function getAppliedMigrations()
    {
        $statement = $this->pdo->query("SELECT migration FROM migrations");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $this->pdo->exec("INSERT INTO migrations (migration) VALUES $str");
    }
}