<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OrdersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrdersRepository extends EntityRepository
{
    /**
     * Renvoi les dernières commandes.
     *
     * @param integer $count Nombre d'élément à afficher
     * @return array Query result
     */
    public function getLastOrder($count)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = 1')
            ->andWhere('o.delivdate < ' . date('d-m-Y'))
            ->orderBy('o.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Renvoi les dernières commandes.
     *
     * @param integer $count Nombre d'élément à afficher
     * @return array Query result
     */
    public function getLastDelivery($count)
    {
        $query = $this->createQueryBuilder('o')
            ->where('o.status = 1')
            ->andWhere('o.delivdate >= ' . date('d-m-Y'))
            ->orderBy('o.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery();

        return $query->getResult();
    }
}
