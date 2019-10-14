<?php

session_start();

use App\RouterApplication;
use Symfony\Component\HttpFoundation\Request;

require '../vendor/autoload.php';

$request = Request::createFromGlobals();

$router = new RouterApplication($request);
$router->init();
$router->run();