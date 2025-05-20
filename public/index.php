<?php
require_once '../app/init.php';

use Core\Router;

$router = new Router();
$router->dispatch();