<?php

namespace App\Helpers;

class Server
{

    private $server;

    public function getServer(string $name)
    {
        return $this->server = $_SERVER[$name];
    }
}
