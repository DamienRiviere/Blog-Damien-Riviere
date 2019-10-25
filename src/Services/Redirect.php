<?php

namespace App\Services;

class Redirect
{

    public function redirectModeration($uri)
    {
        $newUri = explode("/", $uri);
        $array = array_splice($newUri, 3, 6);
        $path = implode("/", $array);
        $url = "/" . $path;

        return $url;
    }
}
