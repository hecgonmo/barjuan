<?php

namespace APP\Core\Interfaces;

/**
 * Interfaz que nos garantiza que siempre que la implementemos deberemos sí o sí implémentar el método asociado,
 * garántizando el correcto funcionamiento de la aplicación para la recepción y procesado de la ruta por URI
 */
interface IRequest
{
    /**
     * Función que obtiene la ruta de la URI
     * @return string
     */
    public function getRoute(): string;

    /**
     * Función que obtiene los parámetros de la URI y nos los devuelve en un array
     * @return array
     */
    public function getParams(): array;
}
