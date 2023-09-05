<?php

namespace Core\Interfaces;

interface Model
{
    public function getTable();

    public function getPrimary();

    public function getFillable();

    public function getData();
}