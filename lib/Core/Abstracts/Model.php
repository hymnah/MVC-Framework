<?php

namespace Core\Abstracts;

use Core\QueryBuilder;
use Exceptions\NotFoundException;

abstract class Model implements \Core\Interfaces\Model
{
    protected $table;

    protected $primary;

    protected $data;

    protected $fillable;

    protected static $queryBuilder;

    private static $_instance;

    public function __construct()
    {
        self::$queryBuilder = QueryBuilder::getInstance($this->table);
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @return mixed
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    public function getData()
    {
        return static::getData();
    }

    public static function insert()
    {
        $self = static::getInstance();
        return $self::$queryBuilder->insert($self);
    }

    public static function update()
    {
        $self = static::getInstance();
        return $self::$queryBuilder->update($self);
    }

    public static function test()
    {
        $self = static::getInstance();
        return $self::$queryBuilder->test($self);
    }

    public static function getBy($field = [])
    {
        $self = static::getInstance();
        return $self::$queryBuilder->getBy($self, $field);
    }

    public static function getOneBy($field = [])
    {
        $self = static::getInstance();
        return $self::$queryBuilder->getOneBy($self, $field);
    }

    public function manyToOne()
    {
        if (!in_array('manyToOne', self::getChildFunctions())) {
            return false;
        }
        return static::manyToOne();
    }

    private static function getChildFunctions()
    {
        return array_diff(get_class_methods(static::getInstance()), get_class_methods(self::class));
    }

    public function __call($name, $arguments)
    {
        $action = 'get';
        $idx = '';
        if (stripos($name, 'get') > -1) {
            $idx = str_replace('get', '', $name);
        }

        if (stripos($name, 'set') > -1) {
            $action = 'set';
            $idx = str_replace('set', '', $name);
        }

        $idx = camel_to_snake($idx);
        $idx = ltrim($idx, '_');

        $fillable = $this->getFillable();

        if (null === $idx = array_search($idx, $fillable)) {
            throw new NotFoundException('Method ' . $name . ' of class ' . static::class . ' does not exist');
        }

        if ($action == 'get') {
            return $fillable[$idx];
        }

        if ($action == 'set') {
            if (!isset($fillable[$idx])) {
                throw new NotFoundException('Method ' . $name . ' of class ' . static::class . ' does not exist');
            }
            $this->setData([$fillable[$idx] => $arguments[0]]);
        }
    }
}