<?php

declare(strict_types=1);

namespace APP\Controllers;

use APP\Core\AbstractController;
use APP\Core\EntityManager;
use APP\Entity\LineasPedidosEntity;
use APP\Entity\ProductosEntity;
use APP\Entity\ProveedoresEntity;
use APP\Entity\PedidosEntity;

/**
 * Clase que se encarga de devolvernos una lista con todas los pedidos
 */
class PedidosController extends AbstractController
{

    private EntityManager $em;

    public function __construct()
    {
        $this->em = new EntityManager();
        parent::__construct();
    }

    /**
     * En este caso queremos todos los dato por lo con el modelo vamos a usar el método que nos devuelve todos los datos
     * @return void
     */

    public function form(): void
    {
        //$entityManager = (new EntityManager)->getEntityManager();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Recoger datos del formulario de manera dinámica
            if (isset($_POST['idProveedor']) && $_POST['idProveedor'] !== "") {
                $idProveedor = intval($_POST['idProveedor']);
                if (isset($_POST['detalles'])) $detalles = $_POST['detalles'];

                $pedidosRepository = $this->em->getEntityManager()->getRepository(PedidosEntity::class);
                $pedido = $pedidosRepository->insertNewPedido($idProveedor, $detalles);
                //dump($pedido);
                // Recorrer los campos de productos
                $productos = [];
                $index = 1;
                $insert = false;
                $lineasPedRepository = $this->em->getEntityManager()->getRepository(LineasPedidosEntity::class);
                while (isset($_POST['idProducto' . $index]) && isset($_POST['cantidad' . $index])) {
                    if (
                        $_POST['idProducto' . $index] != "" &&
                        $_POST['cantidad' . $index] != "" &&
                        $_POST['cantidad' . $index] != "0"
                    ) {
                        $idProducto = intval($_POST['idProducto' . $index]);
                        $cantidad = floatval($_POST['cantidad' . $index]);
                        // Guardamos la linea del pedido
                        $lineaPedido = $lineasPedRepository->insertNewLineaPedido($pedido, $idProducto, $cantidad);
                        $index++;
                        $insert = true;
                    } else {
                        $index++;
                    }
                }
                if ($insert == true) {
                    $this->em->getEntityManager()->flush();
                    $lineasPedidos = $lineasPedRepository->findBy(['pedidosId' => $pedido]);
                    $this->pedidoJSON($pedido, $lineasPedidos);
                } else {
                    echo json_encode(['error' => 'No se ha podido crear el pedido por falta de productos']);
                }
            } else {
                echo json_encode(['error' => 'No se ha podido crear el pedido por falta de un proveedor']);
            }
        } else {
            $proveedoresRepository = $this->em->getEntityManager()->getRepository(ProveedoresEntity::class);
            $proveedores = $proveedoresRepository->findAll();
            $productosRepository = $this->em->getEntityManager()->getRepository(ProductosEntity::class);
            $productos = $productosRepository->findAll();
            $this->render(
                "pedidos.html.twig",
                //Le pasamos los parámetros al renderizado que son todos los datos que obtenemos del modelo.
                [
                    'proveedor' => $proveedores,
                    'productos'  => $productos,
                    'title' => 'Pedidos',
                ]
            );
        }
    }

    public function pedidoJSON(PedidosEntity $pedido, array $lineasPedidos): void
    {
        //dump($pedido);
        if (empty($pedido)) {
            echo json_encode(['error' => 'No se ha podido crear el pedido']);
            return;
        } else {
            $lineas = [];
            foreach ($lineasPedidos as $linea) {
                $lineas[] = [
                    'idLinea' => $linea->getIdLinea(),
                    'idPedido' => $linea->getPedidosId()->getIdPedidos(),
                    'producto' => $linea->getProductoId()->getNombre(),
                    'cantidad' => $linea->getCantidad()
                ];
            }
            $pedidoJSON = array(
                'idPedido' => $pedido->getIdPedidos(),
                'proveedor' => $pedido->getProveedorId()->getNombre(),
                'detalles' => $pedido->getDetalles(),
                'fecha' => $pedido->getFecha()->format('Y-m-d H:i:s'),
                'estado' => $pedido->getEstado() ? 'Recibido' : 'Pendiente',
                'lineas_pedido' => $lineas
            );

            echo json_encode($pedidoJSON);
        }
    }

    public function listAll(): void
    {
        $entityManager = (new EntityManager)->getEntityManager();
        $pedidosRepository = $entityManager->getRepository(PedidosEntity::class);
        $pedidosJSON = $pedidosRepository->allJSON();
        echo json_encode($pedidosJSON);
    }
}
