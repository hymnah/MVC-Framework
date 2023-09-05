<?php

namespace Core;

use Exceptions\QueryException;

class QueryBuilder
{
    protected static $table;

    private static $connection;

    private static $_instance;

    public function __construct($table)
    {
        if (!self::$connection) {
            $connect = Db::getInstance();
            self::$connection = $connect::getConn();
        }

        self::$table = $table;
    }

    public static function getInstance($table)
    {
        if (!self::$_instance) {
            self::$_instance = new self($table);
        }
        return self::$_instance;
    }

    public static function insert($obj)
    {
        $data = $obj->getData();
        if (isset($data[$obj->getPrimary()])) {
            throw new QueryException('Primary key exists');
        }

        self::validateFields($obj);

        $self = self::getInstance(self::$table);

        $keys = implode(',', array_keys($obj->getData()));
        $rawValues = array_values($obj->getData());
        $values = [];
        foreach ($rawValues as $rawValue) {
            if (!is_int($rawValue)) {
                $rawValue = '"' . $rawValue . '"';
            }
            $values[] = $rawValue;
        }
        $values = implode(',', $values);

        $query = 'INSERT INTO ' . $obj->getTable() . '(' . $keys . ') VALUES (' . $values . ')';
        $self::$connection->exec($query);
        return $self::$connection->lastInsertId();
    }

    public static function update($obj)
    {
        $data = $obj->getData();

        if (!isset($data[$obj->getPrimary()])) {
            throw new QueryException('Primary key is missing');
        }

        self::validateFields($obj);

        $obj = self::internalGetBy($obj, [
            $obj->getPrimary() => $data[$obj->getPrimary()]
        ])[0];

        $primaryVal = $data[$obj->getPrimary()];
        unset($data[$obj->getPrimary()]);

        $self = self::getInstance(self::$table);
        $fields = [];
        foreach ($data as $key => $datum) {
            if (is_array($datum)) {
                continue;
            }
            if (!is_int($datum)) {
                $datum = '"' . $datum . '"';
            }
            $fields[] = $key . ' = ' . $datum;
        }

        $query = 'UPDATE ' . $obj->getTable() . ' SET ' . implode(',', $fields);
        $query .= ' WHERE ' . $obj->getPrimary() . ' = ' . $primaryVal;
        return $self::$connection->exec($query);
    }

    private static function validateFields($obj)
    {
        $data = $obj->getData();
        $fillables = $obj->getFillable();

        foreach ($data as $key => $field) {
            if ($key == $obj->getPrimary()) {
                continue;
            }

            if (is_array($field)) {
                continue;
            }

            if (!in_array($key, $fillables)) {
                throw new QueryException('Unknown field ' . $key);
            }
        }
    }

    private static function getRelatedData(&$obj)
    {
        if (!$manyToOne = $obj::manyToOne()) {
            return [];
        }
        $manyToOne = new $manyToOne();
        $oneToMany = $obj;
        $onePrimary = $manyToOne->getPrimary();

        $objData = null;
        if (in_array($manyToOne->getPrimary(), array_keys($obj->getData()))) {
            if (!empty($objData = self::internalGetBy($manyToOne, [$onePrimary => $oneToMany->getData()[$onePrimary]]))) {
                $objData = $objData[0]->getData();
            }
        }

        return [$onePrimary => $objData];
    }

    public static function getOneBy($obj, $fields = [])
    {
        if (empty($fields)) {
            throw new QueryException('Fields parameter cannot be empty');
        }

        return self::getBy($obj, $fields)[0];
    }

    private static function internalGetBy($obj, $fields = [])
    {
        if (empty($fields)) {
            throw new QueryException('Fields parameter cannot be empty');
        }

        $whereArr = [];
        foreach ($fields as $key => $field) {
            if (!is_int($field)) {
                $field = '"' . $field . '"';
            }
            $whereArr[] = $key . ' = ' . $field;
        }

        $query = 'SELECT * FROM ' . $obj->getTable() . ' WHERE ' . implode(' AND ', $whereArr);
        $getQry = self::$connection->prepare($query);
        $getQry->execute();
        $getQry->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $getQry->fetchAll();

        $collection = [];
        foreach ($results as $eachResults) {
            $data = [];
            foreach ($eachResults as $key => $eachResult) {
                $data[$key] = $eachResult;
            }
            $obj->setData($data);
            $collection[] = $obj;
        }

        return $collection;
    }

    public static function getBy($obj, $fields = [])
    {
        if (empty($fields)) {
            throw new QueryException('Fields parameter cannot be empty');
        }

        $whereArr = [];
        foreach ($fields as $key => $field) {
            if (!is_int($field)) {
                $field = '"' . $field . '"';
            }
            $whereArr[] = $key . ' = ' . $field;
        }

        $query = 'SELECT * FROM ' . self::$table . ' WHERE ' . implode(' AND ', $whereArr);
        $getQry = self::$connection->prepare($query);
        $getQry->execute();
        $getQry->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $getQry->fetchAll();

        $collection = [];
        foreach ($results as $eachResults) {
            $data = [];
            foreach ($eachResults as $key => $eachResult) {
                $data[$key] = $eachResult;
            }

            $obj->setData($data);
            $obj->setData(self::getRelatedData($obj));

            $collection[] = $obj;
        }

        return $collection;
    }
}