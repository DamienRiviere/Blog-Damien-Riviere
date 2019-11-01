<?php

namespace App\Controller;

class CustomExceptionController extends Controller
{

    private $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
        parent::__construct();
    }

    public function error404()
    {
        $this->twig->display('exception/error404.html.twig', [
            'exception' => $this->exception
        ]);
    }
}
