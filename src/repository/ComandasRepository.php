<?php

declare(strict_types=1);

namespace APP\Repository;

use APP\Entity\ComandasEntity;
use APP\Entity\MesaEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;

/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class ComandasRepository extends EntityRepository
{
    // Aquí no es necesario que implementemos nada si no lo necesitamos, al extender del EntityRespository ya tenemos lo necesarío.
    public function insertComanda(array $data): ComandasEntity|String
    {
        if (isset($data['mesa'])  && isset($data['comensales'])) {
            if (intval($data['mesa']) != 0  && intval($data['comensales']) != 0) {
                $idMesa = intval($data['mesa']);
                $comensales = intval($data['comensales']);
                $mesaRepository = $this->getEntityManager()->getRepository(MesaEntity::class);
                $mesa = $mesaRepository->findOneBy(['idMesa' => $idMesa]);
                if ($mesa != null || $mesa != '') {
                    $comensalesMesa = $mesa->getComensales();
                    if (!($comensales > $comensalesMesa)) {
                        $detalles = $data['detalles'];
                        $fecha = $data['fecha'];
                        $fecha = DateTime::createFromFormat("d/m/Y H:i:s", $fecha);
                        $fecha->format('Y-m-d H:i:s');
                        $comanda = new ComandasEntity();
                        $comanda->setFecha($fecha);
                        $comanda->setMesaId($mesa);
                        $comanda->setComensales($comensales);
                        $comanda->setDetalles($detalles);
                        $comanda->setFecha($fecha);
                        $this->getEntityManager()->persist($comanda);
                        return $comanda;
                    } else {
                        return $msg =  "La " . $mesa->getNombre() . " no esta lista para " . $comensales . " personas, solo para " . $comensalesMesa;
                    }
                } else {
                    return $msg = "No existe la mesa " . $idMesa;
                }
            } else {
                return $msg = 'La mesa y los comensales no pueden ser 0';
            }
        } else {
            return $msg = 'error en la configuración de las mesas y los comensales, falta un dato';
        }
    }


    public function updateComanda(array $data): ComandasEntity|String
    {
        if (isset($data['mesa'])  && isset($data['comensales'])) {
            if (intval($data['mesa']) != 0  && intval($data['comensales']) != 0) {
                $idMesa = intval($data['mesa']);
                $comensales = intval($data['comensales']);
                //dump($data['mesa']);
                $mesaRepository = $this->getEntityManager()->getRepository(MesaEntity::class);
                $mesa = $mesaRepository->findOneBy(['idMesa' => $idMesa]);
                if ($mesa != null || $mesa != '') {
                    //dump($mesa);
                    $comensalesMesa = $mesa->getComensales();
                    if (!($comensales > $comensalesMesa)) {
                        $detalles = $data['detalles'];
                        $fecha = $data['fecha'];
                        $fecha = DateTime::createFromFormat("d/m/Y H:i:s", $fecha);
                        $fecha->format('Y-m-d H:i:s');

                        $comanda = $this->findOneBy(['fecha' => $fecha, 'mesaId' => $mesa]);
                        $comanda->setFecha($fecha);
                        $comanda->setMesaId($mesa);
                        $comanda->setComensales($comensales);
                        $comanda->setDetalles($detalles);
                        $comanda->setFecha($fecha);
                        $this->getEntityManager()->persist($comanda);
                        //$this->getEntityManager()->flush();
                        return $comanda;
                    } else {
                        return $msg =  "La " . $mesa->getNombre() . " no esta lista para " . $comensales . " personas, solo para " . $comensalesMesa;
                    }
                } else {
                    return $msg = "No existe la mesa " . $idMesa;
                }
            } else {
                return $msg = 'La mesa y los comensales no pueden ser 0';
            }
        } else {
            return $msg = 'error en la configuración de las mesas y los comensales, falta un dato';
        }
    }

    public function comandaEntregada(ComandasEntity $comanda): ComandasEntity
    {
        if ($comanda->getEstado() == true) {
            $comanda->setEstado(false);
            $this->getEntityManager()->persist($comanda);
            $this->getEntityManager()->flush();
        }
        return $comanda;
    }
}
