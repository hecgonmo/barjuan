<?php

declare(strict_types=1);

namespace APP\Repository;

use APP\Entity\ComandasEntity;
use APP\Entity\TicketsEntity;
use Doctrine\ORM\EntityRepository;

/**
 * Únicamente tenemos que extender del EntityRepository de Doctrine para que el repositorio contenga los metodos estandars
 * de Doctrine y podremos trabajar con él una vez que en la entidad indiquemos el repositorío al que esta linkado.
 */
class TicketsRepository extends EntityRepository
{
    // Aquí no es necesario que implementemos nada si no lo necesitamos, al extender del EntityRespository ya tenemos lo necesarío.

    public function insertNewTicket(ComandasEntity $comanda, float $suma): TicketsEntity
    {
        $ticket = new TicketsEntity();
        $ticket->setComandaId($comanda);
        $ticket->setFecha(new \DateTime());
        $ticket->setImporte(floatval($suma));
        $this->getEntityManager()->persist($ticket);
        $this->getEntityManager()->flush();
        return $ticket;
    }
}
