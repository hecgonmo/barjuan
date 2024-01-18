<?php

declare(strict_types=1);

namespace APP\Repository;

use App\Core\EntityManager;
use APP\Entity\ProductosEntity;
use APP\Entity\StockEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;

/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class StockRepository extends EntityRepository
{
    // Aquí no es necesario que implementemos nada si no lo necesitamos, al extender del EntityRespository ya tenemos lo necesarío.

    public function findByDate(DateTime $fecha, array $productos): array
    {

        $stockDate = [];
        foreach ($productos as $producto) {
            $allStock = $this->findBy(['productoId' => $producto], ['fecha' => 'DESC']);
            foreach ($allStock as $stock) {
                if ($stock->getFecha()->format('Y-m-d') == $fecha->format('Y-m-d')) {
                    $stockDate[] = $stock;
                    break;
                }
            }
        }
        return $stockDate;
    }

    public function findByLastDate(array $productos): array
    {
        $lastStock = [];
        foreach ($productos as $producto) {
            $allStock = $this->findBy(['productoId' => $producto], ['fecha' => 'DESC']);
            $lastStock[] = $allStock[0];
        }
        return $lastStock;
    }
}
  /* Ejemplo queryBuilder
  $stock = $stockRepository->createQueryBuilder('s')
            ->where('s.fecha >= :fechaInicio AND s.fecha < :fechaFin')
            ->setParameter('fechaInicio', $fecha->format('Y-m-d'))
            ->setParameter('fechaFin', $fecha->modify('+1 day')->format('Y-m-d'))
            ->getQuery()
            ->getResult();*/