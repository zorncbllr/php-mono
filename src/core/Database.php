<?php

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $config = (require __DIR__ . '/../config/config.php')['database'];

            if ($config['dbname'] !== 'sample_db') {
                $this->pdo = self::getPDOInstance();
            }
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    private static function getPDOInstance(): PDO
    {
        $config = (require __DIR__ . '/../config/config.php')['database'];
        $dsn = "mysql:" . http_build_query($config, "", ";");

        return new PDO($dsn, $config["user"], $config["password"], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public static function query(string $query, array $params = []): array
    {
        $pdo = self::getPDOInstance();

        $statement = $pdo->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll();
    }

    private static function trimClassName(Model $model): array
    {
        $attributes = array_filter(
            (array) $model,
            fn($attr) => $attr !== null
        );

        $keys = array_map(
            fn($attr) => trim(str_replace(get_class($model), "", $attr)),
            array_keys($attributes)
        );

        $values = array_values($attributes);
        $attributes = [];

        for ($i = 0; $i < sizeof($keys); $i++) {
            $attributes[$keys[$i]] = $values[$i];
        }

        return $attributes;
    }
    public static function create(Model $model): bool
    {
        $pdo = $GLOBALS['database']->getPDO();
        $attributes = self::trimClassName($model);

        $table = lcfirst(get_called_class()) . "s";

        $query = "insert into `$table` (";

        $attach_attributes = function (string $prep = "")
        use (&$attributes, &$query) {
            $tag = $prep ? "" : "`";
            foreach (array_keys($attributes) as $key) {
                $query .= $tag . $prep . $key . $tag . ($key != array_key_last($attributes) ? ", " : ") ");
            }
        };

        $attach_attributes();
        $query .= "values (";
        $attach_attributes(":");

        try {
            $statement = $pdo->prepare($query);
            $statement->execute($attributes);
            return true;
        } catch (PDOException $_) {
            return false;
        }
    }

    public static function migrateModel(string $config): bool
    {
        $pdo = self::getPDOInstance();
        $table = lcfirst(get_called_class()) . "s";

        try {
            $query = "CREATE TABLE `{$table}` ( {$config} )";
            $pdo->exec($query);
            echo "\nIniatilizing...\nDatabase initialization process complete.\nTable creation successfully executed.\n";
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() === "42S01") {
                $dbname = (require __DIR__ . '/../config/config.php')['database']['dbname'];
                return self::alterTable($pdo, $config, [
                    'table' => $table,
                    'dbname' => $dbname
                ]);
            }

            return false;
        }
    }

    private static function alterTable(PDO $pdo, string $table_config, array $params): bool
    {
        try {
            $config = explode(",", $table_config);
            // ALTER TABLE `records` CHANGE `title` `title` VARCHAR(10) NOT NULL;
            $query = "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = :dbname AND table_name = :table;";

            $statement = $pdo->prepare($query);
            $statement->execute($params);

            $table_size = $statement->fetch()['COUNT(*)'];

            $query = "ALTER TABLE `{$params['table']}`";

            echo "\nUpdating Table...\n";

            if ($table_size === sizeof($config)) {
                for ($i = 0; $i < sizeof($config); $i++) {
                    $column = trim(explode(' ', $config[$i])[0]);
                    $column_config = str_replace($column, '', trim($config[$i]));

                    if (!str_contains($column_config, "PRIMARY KEY")) {
                        $query .= " CHANGE `{$column}` `{$column}`{$column_config}" . ($i < sizeof($config) - 1 ? "," : ";");
                    }
                }

                $statement = $pdo->prepare($query);
                $statement->execute();
                echo "Table updated successfully.\n";
                return true;
            }

            $pdo->exec("DROP TABLE `{$params['table']}`");

            return self::migrateModel($table_config);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function initModels()
    {
        include_once __DIR__ . '/Model.php';
        $modelsPath = __DIR__ . "/../models";
        $iterator = new DirectoryIterator($modelsPath);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $modelClass = $file->getBasename(".php");
                include_once "{$modelsPath}/{$modelClass}.php";
                eval("{$modelClass}::init{$modelClass}();");
            }
        }
    }

    public static function update(Model $model): bool
    {
        $params = self::trimClassName($model);
        try {
            $pdo = $GLOBALS['database']->getPDO();

            $table = strtolower(get_called_class()) . "s";
            $query = "UPDATE `$table` SET ";

            foreach ($params as $key => $value) {
                $query .= "`$key` = :$key" . ($key != array_key_last($params) ? ", " : " ");
            }

            $query .= " WHERE `$table`.`id` = :id";

            $statement = $pdo->prepare($query);

            $statement->execute([...$params, "id" => $params['id']]);

            return true;
        } catch (PDOException $_) {
            return false;
        }
    }

    public static function delete(Model | int $target): bool
    {
        $id = is_int($target) ? $target : self::trimClassName($target)["id"];
        try {
            $pdo = $GLOBALS['database']->getPDO();
            $table = lcfirst(get_called_class()) . "s";
            $query = "DELETE FROM `$table` WHERE `$table`.`id` = :id";

            $statement = $pdo->prepare($query);
            $statement->execute(["id" => $id]);

            return true;
        } catch (PDOException $_) {
            return false;
        }
    }
}
