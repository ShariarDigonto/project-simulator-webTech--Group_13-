<?php

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/core/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});


require_once __DIR__ . '/config/config.php';


$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'AuthController';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

if (!class_exists($controllerName)) {
    die("Controller $controllerName not found");
}

$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    die("Action $action not found");
}

$controller->$action();