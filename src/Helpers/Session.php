<?php

namespace App\Helpers;

class Session
{

    private $session;

    public function getSession()
    {
        return $this->session = $_SESSION;
    }

    public function getItem(string $name)
    {
        return $this->session = $_SESSION[$name];
    }

    public function setSession(string $name, string $message): void
    {
        $this->session = $_SESSION[$name] = $message;
    }

    public function deleteItem(string $name)
    {
        unset($_SESSION[$name]);
    }
}
