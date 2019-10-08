<?php

namespace App\Controller;

class HomeController extends Controller {

    public function home() {
        $this->twig->display('index.twig');
    }

}