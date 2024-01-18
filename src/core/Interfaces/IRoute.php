<?php

namespace APP\Core\Interfaces;

/**
 * Interfaz que nos garantiza que siempre que la implementemos deberemos sí o sí implémentar el método asociado,
 * garántizando el correcto funcionamiento de la aplicación obteniendo las rutas predefinidas de la aplicación.
 */
interface IRoute
{
    /**
     * Nos devuelve toda la colección de rutas que estan predefinidas en la aplicación
     * @return array
     */
    public function getRoutes(): array;
}
