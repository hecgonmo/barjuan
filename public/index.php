<?php

require_once "../vendor/autoload.php";

use APP\Core\Dispatcher;
use APP\Core\RouteCollection;
use APP\Core\Request;

//Creamos un objeto que contenga todas las rutas predefinidas en la aplicación
$routes = new RouteCollection();
//Creamos un objeto que contenga la ruta y parámetros que hemos recibido desde el navegador.
$request = new Request();
$dispatcher = new Dispatcher($routes, $request);
