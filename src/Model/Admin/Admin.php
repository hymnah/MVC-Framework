<?php

namespace Model\Admin;

use Core\Abstracts\Model;

class Admin extends Model
{
    protected $table = 'admin';

    protected $primary = 'admin_id';

    protected $fillable = [
        'username',
        'password'
    ];

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