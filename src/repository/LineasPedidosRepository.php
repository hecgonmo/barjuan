<?php

declare(strict_types=1);

namespace APP\Repository;

use Doctrine\ORM\EntityRepository;
use APP\Entity\PedidosEntity;
use APP\Entity\StockEntity;
use APP\Entity\ProductosEntity;
use APP\Entity\LineasPedidosEntity;



/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class LineasPedidosRepository extends EntityRepository
{
    public function insertNewLineaPedido(PedidosEntity $pedido, int $idProducto, float $cantidad): LineasPedidosEntity
    {
        try {
            $entityManager = $this->getEntityManager();
            $productosRepository = $entityManager->getRepository(ProductosEntity::class);
            $stockRepository = $entityManager->getRepository(StockEntity::class); ////////
            $producto = $productosRepository->find($idProducto);
            $stock = $stockRepository->findBy(['productoId' => $producto], ['fecha' => 'DESC'])[0]; ///////////
            $cantidadStock = $stock->getCantidad();
            $stock = new StockEntity();
            $stock->setProductoId($producto);
            $stock->setCantidad($cantidadStock + $cantidad);
            $stock->setFecha(new \DateTime());
            //////////////////////////////////////////////
            $lineaPedido = new LineasPedidosEntity();
            $lineaPedido->setPedidosId($pedido);
            $lineaPedido->setProductoId($producto);
            $lineaPedido->setCantidad($cantidad);
            $entityManager->persist($lineaPedido);
            $entityManager->persist($stock);
            return $lineaPedido;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
