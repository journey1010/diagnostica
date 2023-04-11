<?php
include_once 'model/RutasRelativas.php';
require_once _RCONTROLLER . 'RouterController.php';

$router = new Router();
try{
    $router->loadRoutesFromJson();
    $router->handleRequest();
} catch (Throwable $e) {
    $respuesta = ["Message" => "Please! use HTTPS protocol"];
    echo (json_encode($respuesta));
    exit;
}