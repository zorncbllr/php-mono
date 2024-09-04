<?php

abstract class Database
{
    static private function getPDO()
    {
        $config = require __DIR__ . '\\..\\config\\config.php';
        $dsn = "mysql:" . http_build_query($config, "", ";");

        return new PDO($dsn, $config["user"], $config["password"], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    public static function query(string $query, array $params = []): array
    {
        $pdo = self::getPDO();
        $statement = $pdo->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll(); 
    }
    public static function create(Model $model): bool
    {
        $pdo = self::getPDO();

        $attributes = array_filter((array) $model, fn($attr) => $attr !== null);

        $table = lcfirst(get_called_class());

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

    public static function createTable(string $config)
    {
        $pdo = self::getPDO();

        $query = "CREATE TABLE IF NOT EXISTS `" . lcfirst(get_called_class()) . "` ( " . $config . " )";
        $pdo->exec($query);
    }
    public static function initModels()
    {
        $modelsPath = __DIR__ . "\\..\\models";
        $iterator = new DirectoryIterator($modelsPath);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $modelClass = $file->getBasename(".php");
                new $modelClass();
            }
        }
    }

    public static function update(int $id, array $params): bool
    {
        try {
            $pdo = self::getPDO();

            $table = strtolower(get_called_class());
            $query = "UPDATE `$table` SET ";

            foreach ($params as $key => $value) {
                $query .= "`$key` = :$key" . ($key != array_key_last($params) ? ", " : " ");
            }

            $query .= " WHERE `$table`.`id` = :id";

            $statement = $pdo->prepare($query);

            $statement->execute([...$params, "id" => $id]);

            return true;
        } catch (PDOException $_) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = self::getPDO();
            $table = lcfirst(get_called_class());
            $query = "DELETE FROM `$table` WHERE `$table`.`id` = :id";

            $statement = $pdo->prepare($query);
            $statement->execute(["id" => $id]);

            return true;
        } catch (PDOException $_) {
            return false;
        }
    }
}
