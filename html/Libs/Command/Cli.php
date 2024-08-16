<?php

require_once __DIR__.'/../../Config/DirectorySettings.php';
require_once __DIR__.'/../AutoLoader.php';

use Config\DirectorySettings;
use Libs\DB\Database;
use Libs\AutoLoader;
use Libs\DB\Migration\MigrationManager;
use Libs\Config\EnvLoader;

$auto_loader = new AutoLoader(DirectorySettings::APPLICATION_ROOT_DIR);
$auto_loader->run();

$env_loader = new EnvLoader();
$env_loader->loadEnv();

$command = $argv[1] ?? null;

switch ($command) {
    case 'create:database':
        $db = new Database();
        $db->createDatabase();
        echo "Database created successfully.\n";
        break;

    case 'create:migrations-table':
        $manager = new MigrationManager();
        $manager->createMigrationsTable();
        echo "Migrations table created successfully.\n";
        break;

    case 'create:migration':
        $migrationName = $argv[2] ?? null;
        if (is_null($migrationName)) {
            echo "Please provide a migration name.\n";
            exit(1);
        }
        createMigrationFile($migrationName);
        echo "Migration file created: $migrationName\n";
        break;

    case 'migrate':
        $manager = new MigrationManager();
        $manager->migrate();
        break;

    case 'rollback':
        $manager = new MigrationManager();
        $manager->rollback();
        break;

    default:
        echo "Unknown command.\n";
        echo "Available commands:\n";
        echo "  migrate               Run all pending migrations\n";
        echo "  create:migrations-table  Create the migrations table\n";
        break;
}

function createMigrationFile($name) {
    $timestamp = date('YmdHis');
    $className = ucfirst($name);
    $fileName = __DIR__ . "/../../DB/migrations/{$timestamp}_{$name}.php";

    $template = <<<PHP
<?php

use DB\Migration\Migration;
use PDO;

class {$className} extends Migration
{
    private \$pdo;

    public function __construct()
    {
        \$this->pdo = new PDO(
            "mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function up() {
        // TODO: Add your migration logic here
    }

    public function down() {
        // TODO: Add your rollback logic here
    }
}

PHP;

    file_put_contents($fileName, $template);
}