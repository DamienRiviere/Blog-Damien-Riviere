<?php

namespace App;

use Symfony\Component\Routing\Router;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Loader\YamlFileLoader;

class RouterApplication
{

    protected $request;

    protected $router;
    
    protected const CONTROLLER_PATH = "App\\Controller\\";

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function init()
    {
        $filelocator = new FileLocator([dirname(__DIR__) . DIRECTORY_SEPARATOR . "config"]);
        $context = new RequestContext();
        $context->fromRequest($this->request);
        $loader = new YamlFileLoader($filelocator);

        $this->router = new Router($loader, 'routes.yaml', [], $context);
    }

    public function run()
    {
        try {
            $params = $this->router->match($this->request->getPathInfo());
            $controllerName = explode('::', $params['_controller']);
            $controller = self::CONTROLLER_PATH . $controllerName[0];
            $method = $controllerName[1];
            
            $controller = $this->instanciateController($controller);
            $params = $this->cleanParams($params);
        
            return call_user_func_array([$controller, $method], $params);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    
    private function instanciateController(string $controller)
    {
        return new $controller();
    }

    public function cleanParams(array $params)
    {
        unset($params['_route']);
        unset($params['_controller']);

        return $params;
    }
}
