<?php

declare(strict_types=1);

namespace APP\Repository;

use Doctrine\ORM\EntityRepository;
use APP\Entity\PedidosEntity;
use APP\Entity\ProveedoresEntity;
use APP\Core\EntityManager;

/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class PedidosRepository extends EntityRepository
{
    // Aquí no es necesario que implementemos nada si no lo necesitamos, al extender del EntityRespository ya tenemos lo necesarío.
    public function insertNewPedido(int $idProveedor, string $detalles): PedidosEntity
    {
        try {
            $entityManager = $this->getEntityManager();
            $proveedoresRepository = $entityManager->getRepository(ProveedoresEntity::class);
            $proveedor = $proveedoresRepository->findOneBy(['idProveedor' => $idProveedor]);
            $pedidos = new PedidosEntity();
            $fecha = new \DateTime();
            $fecha->format('Y-m-d H:i:s');
            $pedidos->setProveedorId($proveedor);
            $pedidos->setFecha($fecha);
            $pedidos->setDetalles($detalles);
            $entityManager->persist($pedidos);


            return $pedidos;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            echo "El pedido ya existe";
        }
    }

    public function allJSON(): array
    {
        try {
            $entityManager = $this->getEntityManager();
            $pedidosRepository = $entityManager->getRepository(PedidosEntity::class);
            $pedidos = $pedidosRepository->findAll();
            foreach ($pedidos as $pedido) {
                $lineasPedidos = $pedido->getLineasPedidos();
                $lineas = [];
                foreach ($lineasPedidos as $linea) {
                    $lineas[] = [
                        'idLinea' => $linea->getIdLinea(),
                        'idPedido' => $linea->getPedidosId()->getIdPedidos(),
                        'producto' => $linea->getProductoId()->getNombre(),
                        'estado' => $linea->getEntregado() ? 'Recibido' : 'Pendiente',
                        'cantidad' => $linea->getCantidad()
                    ];
                }
                $pedidosJSON[] = array(
                    'idPedido' => $pedido->getIdPedidos(),
                    'proveedor' => $pedido->getProveedorId()->getNombre(),
                    'detalles' => $pedido->getDetalles(),
                    'fecha' => $pedido->getFecha()->format('Y-m-d H:i:s'),
                    'estado' => $pedido->getEstado() ? 'Recibido' : 'Pendiente',
                    'lineas_pedido' => $lineas
                );
            }
            return $pedidosJSON;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
