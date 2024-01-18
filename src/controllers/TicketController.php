<?php

declare(strict_types=1);

namespace APP\Controllers;

use APP\Core\AbstractController;
use APP\Core\EntityManager;
use APP\Entity\ComandasEntity;
use APP\Entity\LineasComandasEntity;
use APP\Entity\TicketsEntity;
use DateTime;

/**
 * Clase que se encarga de devolvernos una lista con todas los productos
 */
class TicketController extends AbstractController
{

    private EntityManager $em;
    private MainController $main;

    public function __construct(EntityManager $entityManager, MainController $main)
    {
        //Vamos a invocar con el entityManager y a cogerlo para poder utilizarlo
        $this->em = $entityManager;
        $this->main = $main;
        parent::__construct();
    }

    public function crearTicket(): void
    {
        try {
            //Llamamos al modelo para poder gestionar los datos
            if (strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
                $jsonData = file_get_contents('php://input');
                $data = json_decode($jsonData, true);
                //dump($data);
                // Verifica si la decodificación fue exitosa
                if (!is_null($data)) {
                    if (
                        isset($data['idComanda']) &&
                        !empty($data['idComanda']) &&
                        !is_null($data['idComanda'])
                    ) {
                        $eM = $this->em->getEntityManager();
                        $comandasRespository = $eM->getRepository(ComandasEntity::class);
                        $comanda = $comandasRespository->find($data['idComanda']);
                        //dump($comanda);
                        if ($comanda instanceof ComandasEntity) {
                            if ($comanda->getEstado() == false) {
                                $lineasComandasRespository = $eM->getRepository(LineasComandasEntity::class);
                                $lineasComandas = $lineasComandasRespository->findBy(['comandaId' => $comanda]);
                                $suma = 0;
                                foreach ($lineasComandas as $linea) {
                                    $suma += $linea->getProductoId()->getPrecio() * $linea->getCantidad();
                                }
                                $ticketsRespository = $eM->getRepository(TicketsEntity::class);
                                $ticket = $ticketsRespository->insertNewTicket($comanda, $suma);
                                if ($ticket instanceof TicketsEntity) {
                                    $this->ticketJSON($ticket, $lineasComandas, 201, 'POST');
                                } else {
                                    $msg = "Error al generar el ticket";
                                    $this->main->jsonResponse($msg, 'POST', 400);
                                }
                            } else {
                                $msg = "La comanda con id: " . $data['idComanda'] . " sigue abierta. No se puede generar un ticket.";
                                $this->main->jsonResponse($msg, 'POST', 400);
                            }
                        } else {
                            // echo $comanda;
                            $msg = "La comanda con id: " . $data['idComanda'] . " no existe.";
                            $this->main->jsonResponse($msg, 'POST', 400);
                        }
                    } else {
                        $msg = "La comanda no están bien configurada, no tiene idComanda";
                        $this->main->jsonResponse($msg, 'POST', 400);
                    }
                } else {
                    $msg = "Error al decodificar el JSON.";
                    $this->main->jsonResponse($msg, 'POST', 400);
                }
            } else {
                $msg = "Petición no permitida";
                $this->main->jsonResponse($msg, $_SERVER['REQUEST_METHOD'], 400);
            }
        } catch (\Exception $e) {
            $errorServer = ['error' => 'Error interno del servidor', 'message' => $e->getMessage()];
            echo json_encode($errorServer, http_response_code(500));
            exit();
        }
    }



    public function ticketJSON(TicketsEntity $ticket, array $lineasComandas, int $status = 201, string $method): void
    {
        if (empty($ticket)) {
            echo json_encode(['error' => 'No se ha podido crear el pedido']);
            return;
        } else {
            $lineas = [];
            foreach ($lineasComandas as $linea) {
                $lineas[] = [
                    'idLinea' => $linea->getIdLinea(),
                    //'idComanda' => $linea->getComandaId()->getIdComanda(),
                    'producto' => $linea->getProductoId()->getNombre(),
                    'cantidad' => $linea->getCantidad(),
                    //'entregado' => $linea->getEntregado() ? 'Entregado' : 'Sin Entregar'
                ];
            }
            $ticketJSON = array(
                'id' => $ticket->getIdTicket(),
                'fecha' => $ticket->getFecha()->format('d/m/Y H:i:s'),
                'importe' => floatval($ticket->getImporte()),
                'lineas' => $lineas,
                'method' => $method,
                'status' => $status
            );
            echo json_encode($ticketJSON, http_response_code($status));
        }
    }
}
