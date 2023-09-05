<?php

namespace Model\Test;

use Core\Abstracts\Model;

class Test extends Model
{
    protected $table = 'test';

    protected $primary = 'test_id';

    protected $fillable = ['test_field', 'test_field_2'];

    protected $data = [];

    public function setData($data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function manyToOne()
    {
        return ChildTest::class;
    }
}