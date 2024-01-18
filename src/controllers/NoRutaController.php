<?php

namespace APP\Controllers;

use APP\Core\AbstractController;

class NoRutaController extends AbstractController
{
    public function noRuta($param = null)
    {

        //Ahora usamos el método extendido del AbstractController render para lanzar la plantilla de twig
        // con los parámetros necesarios.
        $this->render(
            "index.html.twig",
            [
                'title' => 'La Ruta no es correcta',
                'descripcion' => 'ERROR: La ruta introducida no existe'
            ]
        );
    }
}
