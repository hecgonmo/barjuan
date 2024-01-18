<?php

declare(strict_types=1);

namespace APP\Core;

use APP\Core\Interfaces\IRoute;

/**
 * Clase que se encarga de entregarnos todas las rutas predefinidas en la aplicación
 */
class RouteCollection implements Interfaces\IRoute
{

    private array $routes;

    public function __construct()
    {
        //cargamos del archivo JSON los datos de las rutas descodificandolos en un array
        $this->routes = json_decode(file_get_contents(__DIR__ . "/../../config/routes.json"), true);
    }

    /**
     * @inheritDoc
     * Nos devuelve un array con todas las rutas y los datos de ellas que están predefinidios en la aplicación
     * en el JSON de rutas
     */
    public function getRoutes(): array
    {
        // TODO: Implement getRoutes() method.
        return $this->routes;
    }
}
