<?php

namespace App\Core;

class Request
{
    private $params;
    private $routeParams;
    private $method;
    private $ip;
    private $agent;
    private $uri;

    public function __construct()
    {
        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = xssClean($value);
        }
        $this->params = $_REQUEST;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->uri = strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function __get($prop)
    {
        if (isset($this->params[$prop])) {
            return $this->params[$prop];
        } else {
            echo "$prop is not defined!";
        }
    }

    public function params()
    {
        return $this->params;
    }

    public function routeParam($key)
    {
        return $this->routeParams[$key];
    }

    public function method()
    {
        return $this->method;
    }

    public function ip()
    {
        return $this->ip;
    }

    public function agent()
    {
        return $this->agent;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function input($key)
    {
        return $this->params[$key] ?? null;
    }

    public function isset($key)
    {
        return isset($this->params[$key]);
    }

    public function redirect($route)
    {
        header('location: ' . siteURL($route));
        exit;
    }

    public function addRouteParam($key, $value)
    {
        $this->routeParams[$key] = $value;
    }
}