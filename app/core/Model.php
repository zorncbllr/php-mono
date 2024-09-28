<?php

abstract class Model extends Database
{
    public static function find(array $param = [])
    {
        $query = "select * from " . lcfirst(get_called_class()) . "s";

        if (empty($param)) {
            return parent::query($query);
        }

        $key = key($param);
        $query .= " where $key = :$key";

        return parent::query($query, $param);
    }

    public static function findById(int | string $id)
    {
        return self::find(["id" => $id]);
    }
}
