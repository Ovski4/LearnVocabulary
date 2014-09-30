<?php

namespace Ovski\LanguageBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TranslationRepository
 */
class TranslationRepository extends EntityRepository
{
    /**
     * Get query builder
     *
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return QueryBuilder
     */
    public function getQueryBuilder($params = array(), $orderBy = array())
    {
        $qb = $this->createQueryBuilder('t');

        foreach ($params as $key => $value) {
            $qb
                ->andWhere(sprintf('t.%s = :param'.$key, $key))
                ->setParameter('param'.$key, $value)
            ;
        }
        foreach ($orderBy as $sort => $order) {
            $qb->addOrderBy('t.'.$sort, $order);
        }

        return $qb;
    }

    /**
     * Get query
     *
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return Query
     */
    public function getQuery($params = array(), $orderBy = array())
    {
        $qb = $this->getQueryBuilder($params, $orderBy);

        return is_null($qb) ? $qb : $qb->getQuery();
    }

    /**
     * Get
     *
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return DoctrineCollection
     */
    public function get($params = array(), $orderBy = array())
    {
        $q = $this->getQuery($params, $orderBy);

        return is_null($q) ? array() : $q->getResult();
    }
}