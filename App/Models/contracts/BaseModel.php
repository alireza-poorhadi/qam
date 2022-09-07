<?php

namespace App\Models\contracts;

abstract class BaseModel implements CrudInterface
{
    protected $connection;
    protected $table;
    protected $primaryKey = 'id';
    protected $pageSize;
    protected $attributes = []; # the columns of a record in a table

    public function getAttribute($key)
    {
        if (!$key || !array_key_exists($key, $this->attributes)) {
            return null;
        }
        return $this->attributes[$key];
    }

    public function __get($prop)
    {
        return $this->getAttribute($prop);
    }

    public function __set($prop, $value)
    {
        if (!array_key_exists($prop, $this->attributes)) {
            return null;
        }
        $this->attributes[$prop] = $value;
    }
}
