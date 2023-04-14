<?php
require_once 'model/RutasRelativas.php';
require_once _RCONTROLLER . 'RouterController.php';

$router = new Router();
try{
    $router->loadRoutesFromJson();
    $router->handleRequest();
} catch (Throwable $e) {
    $error_message = date('Y-m-d H:i:s : ') . $e->getMessage() . "\n";  
    error_log($error_message, 3 , _ROOT_PATH . '/log/error.log');
    $respuesta = ["Message" => "Please! use HTTPS protocol"];
    echo (json_encode($respuesta));
    exit;
}