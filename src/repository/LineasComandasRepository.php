<?php

declare(strict_types=1);

namespace APP\Repository;

use APP\Entity\ComandasEntity;
use APP\Entity\LineasComandasEntity;
use APP\Entity\ProductosEntity;
use APP\Entity\StockEntity;
use Doctrine\ORM\EntityRepository;
use DateTime;

/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class LineasComandasRepository extends EntityRepository
{
    // Aquí no es necesario que implementemos nada si no lo necesitamos, al extender del EntityRespository ya tenemos lo necesarío.
    public function insertNewLineasComanda(array $linea, ComandasEntity $comanda): String|LineasComandasEntity
    {
        if (intval($linea['producto']) != 0) {
            if (floatval($linea['cantidad']) != 0) {
                $ProductoRespository = $this->getEntityManager()->getRepository(ProductosEntity::class);
                $StockRespository = $this->getEntityManager()->getRepository(StockEntity::class);
                $producto = $ProductoRespository->findOneBy(['idProducto' => $linea['producto']]);
                if ($producto != null || $producto != '') {
                    $stock = $StockRespository->findOneBy(['productoId' => $producto]);
                    if (floatval($linea['cantidad']) <= $stock->getCantidad()) {
                        $lineaComanda = new LineasComandasEntity();
                        $lineaComanda->setComandaId($comanda);
                        $lineaComanda->setProductoId($producto);
                        $lineaComanda->setCantidad(floatval($linea['cantidad']));
                        $this->getEntityManager()->persist($lineaComanda);
                        return $lineaComanda;
                    } else {
                        return $msg = 'La cantidad es superior al stock disponible del producto ' . $producto->getNombre() . ' con Id de producto = ' . $producto->getIdProducto();
                    }
                } else {
                    return $msg = 'El producto con Idproducto ' . $linea['producto'] . ' no existe';
                }
            } else {
                return $msg = 'La cantidad del producto ' . $linea['producto'] . ' no puede ser 0';
            }
        } else {
            return $msg = 'El producto no puede tener un id 0';
        }
    }

    public function updateLineasComanda(array $linea, ComandasEntity $comanda): String|LineasComandasEntity
    {
        //dump($linea['producto']);
        if (intval($linea['producto']) != 0) {
            if (floatval($linea['cantidad']) >= 0) {
                $ProductoRespository = $this->getEntityManager()->getRepository(ProductosEntity::class);
                $StockRespository = $this->getEntityManager()->getRepository(StockEntity::class);
                $producto = $ProductoRespository->findOneBy(['idProducto' => $linea['producto']]);
                //dump($producto);
                if (isset($producto) || !empty($producto) || !is_null($producto)) {
                    $stock = $StockRespository->findOneBy(['productoId' => $producto]);
                    //dump($stock);
                    if (floatval($linea['cantidad']) <= $stock->getCantidad()) {
                        $lineaComanda = $this->findOneBy(['comandaId' => $comanda, 'productoId' => $producto]);
                        //dump($lineaComanda);
                        if (!isset($lineaComanda) || empty($lineaComanda) || is_null($lineaComanda)) {
                            $lineaComanda = new LineasComandasEntity();
                            $lineaComanda->setComandaId($comanda);
                            $lineaComanda->setProductoId($producto);
                            $lineaComanda->setCantidad(floatval($linea['cantidad']));
                        } else {
                            if ($linea['cantidad'] == 0) {
                                //$this->getEntityManager()->remove($lineaComanda); Podríamos borrarla.
                                $lineaComanda->setCantidad(floatval($linea['cantidad']));
                                $lineaComanda->setEntregado(true);
                            }
                            $lineaComanda->setCantidad(floatval($linea['cantidad']));
                            $lineaComanda->setEntregado(false);
                        }
                        $this->getEntityManager()->persist($lineaComanda);
                        return $lineaComanda;
                    } else {
                        return $msg = 'La cantidad es superior al stock disponible del producto ' . $producto->getNombre() . ' con Id de producto = ' . $producto->getIdProducto();
                    }
                } else {
                    return $msg = 'El producto con Idproducto ' . $linea['producto'] . ' no existe';
                }
            } else {
                return $msg = 'La cantidad del producto ' . $linea['producto'] . ' no puede ser 0';
            }
        } else {
            return $msg = 'El producto no puede tener un id 0';
        }
    }

    public function lineaComandaEntregada(LineasComandasEntity $lineaComanda): LineasComandasEntity|String
    {
        $stockRepository = $this->getEntityManager()->getRepository(StockEntity::class);
        $stock = $stockRepository->findBy(['productoId' => $lineaComanda->getProductoId()], ['fecha' => 'DESC'])[0]; ///////////
        $cantidadStock = $stock->getCantidad();

        if ($cantidadStock < $lineaComanda->getCantidad()) {
            return $msg = 'La cantidad es superior al stock disponible del producto ' . $lineaComanda->getProductoId()->getNombre() . ' con Id de producto = ' . $lineaComanda->getProductoId()->getIdProducto();
        } else {
            $stock = new StockEntity();
            $stock->setProductoId($lineaComanda->getProductoId());
            $stock->setCantidad($cantidadStock - $lineaComanda->getCantidad());
            $stock->setFecha(new \DateTime());
            $lineaComanda->setEntregado(true);
            $this->getEntityManager()->persist($lineaComanda);
            $this->getEntityManager()->persist($stock);
            return $lineaComanda;
        }
    }
}
