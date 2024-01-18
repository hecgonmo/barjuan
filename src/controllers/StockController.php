<?php

declare(strict_types=1);

namespace APP\Controllers;

use APP\Core\AbstractController;
use APP\Core\EntityManager;
use APP\Entity\ProductosEntity;
use APP\Entity\StockEntity;
use DateTime;
use Doctrine\Common\Util\Debug;

/**
 * Clase que se encarga de devolvernos una lista con todas las clientes
 */
class StockController extends AbstractController
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

    public function listAll(): void
    {
        $stockRepository = $this->em->getEntityManager()->getRepository(StockEntity::class);
        $productosRepository = $this->em->getEntityManager()->getRepository(ProductosEntity::class);
        $productos = $productosRepository->findAll();
        if (isset($_POST['fecha'])) {
            $fecha = new DateTime($_POST['fecha']);
            $fecha->setTime(0, 0, 0);
            //dump($fecha);
            $stock = $stockRepository->findByDate($fecha, $productos);
            //dump($stock);
        } else {
            //Llamamos al modelo para poder gestionar los datos
            //$stock = $stockRepository->findAll();
            $stock = $stockRepository->findByLastDate($productos);
        }
        if (empty($stock)) $stock = null;
        //Para este controller vamos a utilizar la plantilla stocklist.html.twig para poder mostrar adecuadamente los datos.
        //dump($stock);
        $this->render(
            "stocklist.html.twig",
            //Le pasamos los parámetros al renderizado que son todos los datos que obtenemos del modelo.
            [
                "resultados" => $stock,
                'title' => 'Stock'
            ]
        );
    }
}
