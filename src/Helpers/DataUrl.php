<?php

namespace App\Helpers;

class DataUrl
{
    private $dataUrl;

    public function getDataUrl(string $name)
    {
        return $this->dataUrl = $_GET[$name];
    }
}
