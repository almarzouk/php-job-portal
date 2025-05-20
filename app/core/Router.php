<?php
namespace Core;

class Router
{
    public function dispatch()
    {
        $url = $_GET['url'] ?? 'jobs/index';
        $url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

        $controllerParts = explode('-', $url[0]);
        $controllerName = implode('', array_map('ucfirst', $controllerParts)) . 'Controller';

        $method = isset($url[1]) && !is_numeric($url[1]) ? $url[1] : 'index';
        $params = is_numeric($url[1] ?? null) ? array_slice($url, 1) : array_slice($url, 2);

        $controllerPath = "../app/controllers/$controllerName.php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;

            $controller = new $controllerName;

            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                echo "❌ Methode '$method' nicht gefunden.";
            }
        } else {
            echo "❌ Controller '$controllerName' nicht gefunden.";
        }
    }
}