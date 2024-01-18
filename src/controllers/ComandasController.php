<?php

declare(strict_types=1);

namespace APP\Controllers;

use APP\Core\AbstractController;
use APP\Core\EntityManager;
use APP\Entity\ComandasEntity;
use APP\Entity\LineasComandasEntity;
use DateTime;

/**
 * Clase que se encarga de devolvernos una lista con todas los productos
 */
class ComandasController extends AbstractController
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

    public function crearComanda(): void
    {
        try {
            //Llamamos al modelo para poder gestionar los datos
            if (strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
                $jsonData = file_get_contents('php://input');
                // Decodifica el JSON en un array asociativo
                //dump($jsonData);
                $data = json_decode($jsonData, true);
                //dump($data);
                // Verifica si la decodificación fue exitosa
                if (!is_null($data)) {
                    if (
                        isset($data['fecha']) != "" &&
                        isset($data['mesa']) != "" &&
                        isset($data['comensales']) != "" &&
                        isset($data['Lineas']) != ""
                    ) {
                        $fecha = $data['fecha'];
                        if ($this->validateDate($fecha)) {
                            $eM = $this->em->getEntityManager();
                            $comandasRespository = $eM->getRepository(ComandasEntity::class);
                            $comanda = $comandasRespository->insertComanda($data);
                            if ($comanda instanceof ComandasEntity) {
                                //dump($comanda);
                                if (array($data['Lineas'])) {
                                    $lineasComandas = $eM->getRepository(LineasComandasEntity::class);
                                    //dump($comanda);
                                    $flush = true;
                                    $msg = "";
                                    foreach ($data['Lineas'] as $lineaComanda) {
                                        $result = $lineasComandas->insertNewLineasComanda($lineaComanda, $comanda);
                                        //dump($lineaComanda);
                                        if (!($result instanceof LineasComandasEntity)) {
                                            $msg = $result;
                                            $flush = false;
                                            break;
                                        }
                                    }
                                    if ($flush == true) {
                                        $this->em->getEntityManager()->flush();
                                        $lineasComandas = $this->em->getEntityManager()->getRepository(LineasComandasEntity::class)->findBy(['comandaId' => $comanda]);
                                        //dump($lineasComandas);
                                        $this->pedidoJSON($comanda, $lineasComandas, 201, 'POST');
                                    } else {
                                        $this->main->jsonResponse($msg, 'POST', 400);
                                    }
                                } else {
                                    $msg = "La lineas de la comanda no están bien configuradas";
                                    $this->main->jsonResponse($msg, 'POST', 400);
                                }
                            } else {
                                // echo $comanda;
                                $this->main->jsonResponse($comanda, 'POST', 400);
                            }
                        } else {
                            $msg = "La fecha no es correcta no cumple con el formato d/m/Y H:i:s";
                            $this->main->jsonResponse($msg, 'POST', 400);
                        }
                    } else {
                        $msg = "La lineas de la comanda no están bien configuradas, falta algún dato";
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
            // Definir el encabezado de respuesta 500
            //http_response_code(500);
            // Puedes incluir un mensaje en el cuerpo de la respuesta en formato JSON
            $errorServer = ['error' => 'Error interno del servidor', 'message' => $e->getMessage()];
            echo json_encode($errorServer, http_response_code(500));

            // Finalizar la ejecución del script
            exit();
        }
    }

    public function actualizarComanda(): void
    {
        try {
            //Llamamos al modelo para poder gestionar los datos
            if ((strcmp($_SERVER['REQUEST_METHOD'], 'PUT') == 0)) {
                $jsonData = file_get_contents('php://input');
                $data = json_decode($jsonData, true);
                if (!is_null($data)) {
                    if (
                        isset($data['fecha']) != "" &&
                        isset($data['mesa']) != "" &&
                        isset($data['comensales']) != "" &&
                        isset($data['Lineas']) != ""
                    ) {
                        $fecha = $data['fecha'];
                        if ($this->validateDate($fecha)) {
                            $eM = $this->em->getEntityManager();
                            $comandasRespository = $eM->getRepository(ComandasEntity::class);
                            $comanda = $comandasRespository->updateComanda($data);
                            if ($comanda instanceof ComandasEntity) {
                                //dump($comanda);
                                if (array($data['Lineas'])) {
                                    $lineasComandas = $eM->getRepository(LineasComandasEntity::class);
                                    //dump($comanda);
                                    $flush = true;
                                    $msg = "";
                                    foreach ($data['Lineas'] as $lineaComanda) {
                                        $result = $lineasComandas->updateLineasComanda($lineaComanda, $comanda);
                                        //dump($lineaComanda);
                                        if (!($result instanceof LineasComandasEntity)) {
                                            $msg = $result;
                                            $flush = false;
                                            break;
                                        }
                                    }
                                    if ($flush == true) {
                                        $this->em->getEntityManager()->flush();
                                        $lineasComandas = $this->em->getEntityManager()->getRepository(LineasComandasEntity::class)->findBy(['comandaId' => $comanda]);
                                        $this->pedidoJSON($comanda, $lineasComandas, 201, 'PUT');
                                    } else {
                                        $this->main->jsonResponse($msg, 'PUT', 400);
                                    }
                                } else {
                                    $msg = "La lineas de la comanda no están bien configuradas";
                                    $this->main->jsonResponse($msg, 'PUT', 400);
                                }
                            } else {
                                $this->main->jsonResponse($comanda, 'PUT', 400);
                            }
                        } else {
                            $msg = "La fecha no es correcta no cumple con el formato d/m/Y H:i:s";
                            $this->main->jsonResponse($msg, 'PUT', 400);
                        }
                    } else {
                        $msg = "La lineas de la comanda no están bien configuradas, falta algún dato";
                        $this->main->jsonResponse($msg, 'PUT', 400);
                    }
                } else {
                    $msg = "Error al decodificar el JSON.";
                    $this->main->jsonResponse($msg, 'PUT', 400);
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

    public function entregarComanda(): void
    {
        try {
            if ((strcmp($_SERVER['REQUEST_METHOD'], 'PATCH') == 0)) {
                $jsonData = file_get_contents('php://input');
                $data = json_decode($jsonData, true);
                // Verifica si la decodificación es válida
                if (!is_null($data)) {
                    if (
                        isset($data['idlinea']) != "" &&
                        isset($data['idComanda']) != "" &&
                        isset($data['idProducto']) != "" &&
                        isset($data['cantidad']) != ""
                    ) {
                        //dump($data);
                        $eM = $this->em->getEntityManager();
                        $lineasComandasRespository = $eM->getRepository(LineasComandasEntity::class);
                        $lineaComanda = $lineasComandasRespository->findOneBy(['idlinea' => $data['idlinea']]);
                        if ($lineaComanda instanceof LineasComandasEntity) {
                            if (
                                intval($data['idComanda']) === $lineaComanda->getComandaId()->getIdComanda() &&
                                intval($data['idProducto']) === $lineaComanda->getProductoId()->getIdProducto() &&
                                floatval($data['cantidad']) === $lineaComanda->getCantidad()
                            ) {
                                if ($lineaComanda->getEntregado() == false) {
                                    $lineaComanda = $lineasComandasRespository->lineaComandaEntregada($lineaComanda);
                                    if ($lineaComanda instanceof LineasComandasEntity) {
                                        $comandaRespository = $eM->getRepository(ComandasEntity::class);
                                        $comanda = $comandaRespository->findOneBy(['idComanda' => $lineaComanda->getComandaId()]);
                                        $lineasComandas = $lineasComandasRespository->findBy(['comandaId' => $comanda]);
                                        ////comprobar si todas las lineas de la comanda estan entregadas
                                        $updateComanda = true;
                                        foreach ($lineasComandas as $linea) {
                                            if ($linea->getEntregado() == false) {
                                                $updateComanda = false;
                                                break;
                                            }
                                        }
                                        if ($updateComanda == true) {
                                            $comanda = $comandaRespository->comandaEntregada($comanda);
                                        }
                                        $this->em->getEntityManager()->flush();
                                        $this->pedidoJSON($comanda, $lineasComandas, 201, 'PATCH');
                                    } else {
                                        $msg = "$lineaComanda";
                                        $this->main->jsonResponse($msg, 'PATCH', 400);
                                    }
                                } else {
                                    $msg = "La comanda " . $lineaComanda->getIdlinea() . " ya ha sido entregada";
                                    $this->main->jsonResponse($msg, 'PATCH', 400);
                                }
                            } else {
                                $msg = "Los datos de la linea de la comanda no coinciden";
                                $this->main->jsonResponse($msg, 'PATCH', 400);
                            }
                        } else {
                            $msg = "La linea de la comanda no existe";
                            $this->main->jsonResponse($msg, 'PATCH', 400);
                        }
                    } else {
                        $msg = "Valores de la Linea de la comanda no están bien configuradas, falta algún dato ";
                        $this->main->jsonResponse($msg, $_SERVER['REQUEST_METHOD'], 400);
                    }
                } else {
                    $msg = "Error al decodificar el JSON.";
                    $this->main->jsonResponse($msg, 'PATCH', 400);
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

    function validateDate($date, $format = 'd/m/Y H:i:s'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function pedidoJSON(ComandasEntity $comanda, array $lineasComandas, int $status = 201, string $method): void
    {
        if (empty($comanda)) {
            echo json_encode(['error' => 'No se ha podido crear el pedido']);
            return;
        } else {
            $lineas = [];
            foreach ($lineasComandas as $linea) {
                $lineas[] = [
                    'idLinea' => $linea->getIdLinea(),
                    'idComanda' => $linea->getComandaId()->getIdComanda(),
                    'producto' => $linea->getProductoId()->getNombre(),
                    'cantidad' => $linea->getCantidad(),
                    'entregado' => $linea->getEntregado() ? 'Entregado' : 'Sin Entregar'
                ];
            }
            $comandaJSON = array(
                'idComanda' => $comanda->getIdComanda(),
                'mesa' => $comanda->getMesaId()->getIdMesa(),
                'detalles' => $comanda->getDetalles(),
                'fecha' => $comanda->getFecha()->format('d/m/Y H:i:s'),
                'estado' => $comanda->getEstado() ? 'En Espera' : 'Finalizada',
                'lineas_comanda' => $lineas,
                'method' => $method,
                'status' => $status
            );

            echo json_encode($comandaJSON, http_response_code($status));
        }
    }
}
