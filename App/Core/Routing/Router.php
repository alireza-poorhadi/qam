<?php

namespace App\Core\Routing;

use \App\Core\Request;
use \App\Core\Routing\Route;
use Exception;

class Router
{
    private $request;
    private $routes;
    private $currentRoute;
    private const BASE_NAMESPACE = '\App\Controllers\\';

    public function __construct()
    {
        global $request;
        $this->request = $request;
        $this->routes = Route::routes();
        $this->currentRoute = $this->findRoute();
        # running middleware here
        $this->runGlobalMiddlewares();
        $this->runRoutesMiddleware();
    }

    private function runGlobalMiddlewares()
    {
        // $global = new globalMiddleware();
        // $global->handle();
    }

    private function runRoutesMiddleware()
    {
        if (!is_null($this->currentRoute)) {
            $middlewares = $this->currentRoute['middlewares'];
            foreach ($middlewares as $middleware) {
                $className = 'App\Middleware\\' . $middleware;
                if (!class_exists($className)) {
                    throw new \Exception('Class ' . $className . ' does not exist.');
                }
                $middlewarObj = new $className();
                $middlewarObj->handle();
            }
        }
    }

    private function findRoute()
    {
        foreach ($this->routes as $route) {
            if ($this->regexMatched($route)) {
                return $route;
            }
        }
        return null;
    }

    private function regexMatched($route)
    {
        global $request;
        $routePattern = "/^" . str_replace(['/', '{', '}'], ['\/', '(?<', '>[-%\w]+)'], strtolower($route['uri'])) . "$/";
        $result = preg_match($routePattern, strtolower($this->request->uri()), $matches);
        if (!$result) {
            return false;
        }
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $request->addRouteParam($key, $value);
            }
        }
        return true;
    }

    public function run()
    {
        if (is_null($this->currentRoute)) {
            $this->dispatch404();
        } else {
            if (!$this->isInvalidRequest($this->request, $this->currentRoute)) {
                $this->dispatch405();
            }
        }
        $this->dispatch($this->currentRoute);
    }

    private function isInvalidRequest($request, $currentRoute)
    {
        if (!in_array($request->method(), $currentRoute['method'])) {
            return false;
        }
        return true;
    }

    private function dispatch405()
    {
        header("HTTP/1.0 405 Method Not Allowed");
        die("Method Not Allowed");
    }

    private function dispatch404()
    {
        header("HTTP/1.1 404 Not Found");
        view('errors.404');
        die();
    }

    private function dispatch($route)
    {
        $action = $route['action'];

        # action : null
        if (is_null($action) || empty($action)) {
            return;
        }

        # action : closure
        if (is_callable($action)) {
            $action();
            # call_user_func($action);
        }

        # action : controller@method || ['controller', 'method']
        if (is_string($action)) {
            $action = explode('@', $action);
        }

        if (is_array($action)) {
            $className = self::BASE_NAMESPACE . $action[0];
            $method = $action[1];
            if (!class_exists($className)) {
                throw new \Exception('Class ' . $className . ' does not exist.');
            }
            $controller = new $className();
            if (!method_exists($controller, $method)) {
                throw new \Exception("Method $method does not exist in class $className");
            }
            $controller->{$method}();
        }
    }
}
