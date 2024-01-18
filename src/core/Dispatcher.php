<?php

declare(strict_types=1);

namespace APP\Core;

use APP\Core\Interfaces\IRequest;
use APP\Core\Interfaces\IRoute;

/**
 * Clase que se encarga de gestionar que ruta ha pedido el cliente y que debemos mostrar por pantalla.
 * Para ello, analiza las rutas preconfiguradas y llama al Controlador para que realize su trabajo.
 * Siendo el verdadero cerebro del MVC
 */
class Dispatcher
{
    private array $routeList;
    private IRequest $currentRequest;

    /**
     *  Para poder crear un objeto Dispatcher debemos enviar las rutas de la aplicación y la URI del navegador
     *  para que el dispatcher puéda redirigir al lugar controller correcto con los parámetros adecuados.
     * @param IRoute $routeCollection
     * @param IRequest $request
     */
    public function __construct(IRoute $routeCollection, IRequest $request)
    {
        $this->routeList = $routeCollection->getRoutes();
        $this->currentRequest = $request;
        $this->dispatch();
    }

    /**
     * El cerebro de nuestra aplicación, se encarga de lanzar el controlador adecuado para cada ruta solicitada
     * @return void
     */
    private function dispatch(): void
    {
        //Verificamos que la ruta que hemos recibido está dentro de las rutás de la aplicación
        if (isset($this->routeList[$this->currentRequest->getRoute()])) {
            //Aquí dentro tenemos un texto del tipo AP2\Controller\DetalleController
            $controllerClass = "APP\\Controllers\\" . $this->routeList[$this->currentRequest->getRoute()]["controller"];
            //Es equivalente al texto main o detail
            $action = $this->routeList[$this->currentRequest->getRoute()]["action"];
        } else {
            //En caso de no estar predefinida cargaremos el controlador NoRuta para garantizar que nuestra aplicación
            //siempre tiene una vista que mostrar y creamos el namespace correspondiente para poder instanciarlo.
            $controllerClass = "APP\\Controllers\\NoRutaController";
            $action = "noRuta";
        }
        //Comprobamos que se han enviado o no parámetros por la ruta y lanzamos la acción del controller
        if (!is_null($this->currentRequest->getParams())) {
            $params = $this->currentRequest->getParams();
        } else {
            //No hemos recibimos ningún paramétro.
            $params = null;
        }
        //Instanciamos el controlador que toca
        $controller = new $controllerClass();
        //Ahora ejecutamos el método asociado a la ruta y le pasamos los parámetros.
        $controller->$action(...$params);
    }
}
