<?php

namespace App\Controller;

class PostController extends Controller {

    public function posts() {
        $this->twig->display('index.twig');
    }

}