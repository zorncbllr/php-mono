<?php

namespace Src\Core\Model;

use Database;

abstract class Model extends Database
{
    public static function find(array $param = [])
    {
        $table = strtolower(get_called_class());
        $query = "SELECT * FROM `{$table}s`";

        if (empty($param)) {
            return self::mapper(parent::query($query));
        }

        $query .= " WHERE";

        foreach ($param as $key => $value) {
            $query .= ($key !== array_key_first($param) ? " AND " : " ") . "`{$table}s`.`{$key}` = :{$key}";
        }

        $data = parent::query($query, [...$param]);

        if (empty($data) && !empty($param)) {
            return null;
        }

        if (sizeof($data) === 1) {
            return self::mapper($data[0]);
        }

        return self::mapper($data);
    }


    public static function findById(int | string $id)
    {
        $query = "select * from " . lcfirst(get_called_class()) . "s where id = :id";

        $data = parent::query($query, ["id" => $id]);

        return empty($data) ? null : self::mapper($data[0]);
    }

    private static function mapper(array $data)
    {
        $class = get_called_class();

        if (array_is_list($data)) {
            return array_map(
                fn($dt) => new $class(...$dt),
                $data
            );
        }

        return new $class(...$data);
    }
}
