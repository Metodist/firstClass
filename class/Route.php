<?php

class Route
{

    public static function start()
    {
        $routes = [
            "/" => '../controllers/index.php',
            "/about" => '../controllers/about.php'
        ];

        $route = $_SERVER['REQUEST_URI'];

        if (array_key_exists($route, $routes)) {
            include $routes[$route];
        } else {
            include '../controllers/404.php';
        }


    }

}

?>