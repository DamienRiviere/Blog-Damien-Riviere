<?php

namespace App\Helpers;

class Data
{

    private $data;

    public function getData()
    {
        return $this->data = $_POST;
    }
}
