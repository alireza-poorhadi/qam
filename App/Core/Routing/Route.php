<?php

namespace App\Core\Routing;

class Route
{
    private static $routes = [];

    public static function add($method, $uri, $action = null, $middlewares = [])
    {
        $method = is_array($method) ? $method : [$method];
        $route = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => $middlewares
        ];
        self::$routes[] = $route;
    }

    public static function get($uri, $action = null , $middlewares = [])
    {
        self::add('get', $uri, $action, $middlewares);
    }

    public static function post($uri, $action = null, $middlewares = [])
    {
        self::add('post', $uri, $action, $middlewares);
    }

    public static function put($uri, $action = null, $middlewares = [])
    {
        self::add('put', $uri, $action, $middlewares);
    }

    public static function patch($uri, $action = null, $middlewares = [])
    {
        self::patch('patch', $uri, $action, $middlewares);
    }

    public static function delete($uri, $action = null, $middlewares = [])
    {
        self::add('delete', $uri, $action, $middlewares);
    }

    public static function options($uri, $action = null, $middlewares = [])
    {
        self::add('options', $uri, $action, $middlewares);
    }

    public static function routes()
    {
        return self::$routes;
    }
}
