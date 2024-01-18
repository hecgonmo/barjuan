<?php

declare(strict_types=1);

namespace APP\Controllers;

use APP\Core\AbstractController;
use APP\Core\EntityManager;


class MainController extends AbstractController
{


    private EntityManager $em;


    public function __construct()
    {
        $this->em = new EntityManager();
        parent::__construct();
    }


    /**
     * Esta ruta es la que sale por defecto en la aplicación cuando se inicia.
     * @return void
     */
    public function main(): void
    {
        //Ahora usamos el método extendido del AbstractController render para lanzar la plantilla de twig
        // con los parámetros necesarios.
        $this->render(
            "index.html.twig",
            [
                'title' => ' BAR JUAN',
                'descripcion' => '¡AHORA SOMOS BAR DUMP! Estamos en la calle de la SYMFONYA, 404.'
            ]
        );
    }

    public function jsonResponse(?string $msg, ?string $method, ?int $status): void
    {
        //Establecemos el código de respuesta si no lo hemos recibido por defecto en 400
        if (is_null($status)) {
            $status = 400;
        }

        if (is_null($msg)) {
            $result = 'Petición inválida realizada: ' . date("d-m-Y-H-i-s");
        } else {
            $result = $msg;
        }

        if (is_null($method)) {
            $arrayJson = array(
                'result' => $result,
            );
        } else {
            $arrayJson = array(
                'result' => $result,
                'method' => $method,
                'status' => $status
            );
        }
        $json = json_encode($arrayJson, http_response_code($status));

        echo $json;
    }

    public function readRequest(?string ...$params): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $result = match ($method) {
            //'GET'=>
            'POST' => $this->verificarRuta(...$params),
            'PUT' => $this->actualizarComanda(...$params),
            'PATCH' => $this->entregarComanda(...$params),
                //'DELETE'=>
            default => $this->jsonResponse(null, $method, 400)
        };
    }

    public function crearComanda(?string ...$params): void
    {
        $comanda = new ComandasController($this->em, $this);
        $comanda->crearComanda();
    }

    public function actualizarComanda(?string ...$params): void
    {
        $comanda = new ComandasController($this->em, $this);
        $comanda->actualizarComanda();
    }

    public function entregarComanda(?string ...$params): void
    {
        $comanda = new ComandasController($this->em, $this);
        $comanda->entregarComanda();
    }

    public function crearTicket(?string ...$params): void
    {
        $ticket = new TicketController($this->em, $this);
        $ticket->crearTicket();
    }

    public function verificarRuta(?string ...$params): void
    {
        $ruta = $_SERVER['REQUEST_URI'];
        $ruta = explode('/', $ruta);
        $ruta = $ruta[1];
        $result = match ($ruta) {
            'comanda' => $this->crearComanda(...$params),
            'ticket' => $this->crearTicket(...$params),
            default => $this->jsonResponse(null, null, 400)
        };
    }
}
