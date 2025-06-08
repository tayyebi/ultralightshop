<?php
namespace UltralightShop\Core;

class Router
{
    private $routes = [];

    public function add($slug, callable $handler): void
    {
        $this->routes[$slug] = $handler;
    }

    public function dispatch($slug)
    {
        if (isset($this->routes[$slug])) {
            call_user_func($this->routes[$slug]);
        }
    }
}
