<?php

namespace Model\Test;

use Core\Abstracts\Model;

class ChildTest extends Model
{
    protected $table = 'child_test';

    protected $primary = 'child_test_id';

    protected $fillable = ['child_test_field'];

    protected $data = [];

    public function setData($data = [])
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData()
    {
        return $this->data;
    }

}