<?php

namespace App\Controller;

class HomeController extends Controller
{

    public function home(): void
    {
        $this->twig->display('@home/index.twig');
    }
}
