<?php

declare(strict_types=1);

namespace APP\Core;

use APP\Core\Interfaces\IRequest;

/**
 * Clase que se encarga de entregarnos la ruta y los parámetros solicitados por el cliente e implementa la interfaz IRequest
 */
class Request implements Interfaces\IRequest
{
    private string $route;
    private array $params;

    public function __construct()
    {
        //Lo primero hemos de obtener la ruta del navegador mediante una supervariable  o variables globales.
        $rawRoute = $_SERVER["REQUEST_URI"];
        //Separamos la URI en partes mediante el separador predefinido en la aplicación: "/"
        $rawRouteElements = explode("/", $rawRoute);
        //definimos que es ruta (en este caso será el primer elemento tras el separador) y además le añadimos el
        //separador para poder compararlo con los datos que tenemos en la aplicación almacenados
        $this->route = "/" . $rawRouteElements[1];
        //Guardamos el resto de datos recibidos como parámetros, por lo que serán a partir de la segunda posición.
        $this->params = array_slice($rawRouteElements, 2);
    }

    /**
     * @inheritDoc
     * Este método nos devuelve la ruta actual de la URI.
     */
    public function getRoute(): string
    {
        // TODO: Implement getRoute() method.
        return $this->route;
    }

    /**
     * @inheritDoc
     * Este método nos devuelve los parámetros actuales de la URI
     */
    public function getParams(): array
    {
        // TODO: Implement getParams() method.
        return $this->params;
    }
}
