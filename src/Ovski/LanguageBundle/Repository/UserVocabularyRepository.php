<?php

namespace Ovski\LanguageBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserVocabularyRepository
 */
class UserVocabularyRepository extends EntityRepository
{
    /**
     * getByUserQueryBuilder
     *
     * @param string : user id
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return QueryBuilder
     */
    public function getByUserQueryBuilder($userId, $params = array(), $orderBy = array())
    {
        $qb = $this->createQueryBuilder('v');
        $qb
            ->join('v.users', 'u')
            ->where($qb->expr()->eq('u.id', $userId))
        ;
        foreach ($params as $key => $value) {
            $qb
                ->andWhere(sprintf('v.%s = :param'.$key, $key))
                ->setParameter('param'.$key, $value)
            ;
        }
        foreach ($orderBy as $sort => $order) {
            $qb->addOrderBy('v.'.$sort, $order);
        }

        return $qb;
    }

    /**
     * getByUserQuery
     *
     * Get by user query
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return Query
     */
    public function getByUserQuery($userId, $params = array(), $orderBy = array())
    {
        $qb = $this->getByUserQueryBuilder($userId, $params, $orderBy);

        return is_null($qb) ? $qb : $qb->getQuery();
    }

    /**
     * getOneByUser
     *
     * Get one by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return DoctrineCollection
     */
    public function getOneByUser($userId, $params = array(), $orderBy = array())
    {
        $q = $this->getByUserQuery($userId, $params, $orderBy);

        return is_null($q) ? array() : $q->getSingleResult();
    }

    /**
     * getOneOrNullByUser
     *
     * Get one by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return DoctrineCollection
     */
    public function getOneOrNullByUser($userId, $params = array(), $orderBy = array())
    {
        $q = $this->getByUserQuery($userId, $params, $orderBy);

        return is_null($q) ? array() : $q->getOneOrNullResult();
    }

    /**
     * getByUser
     *
     * Get by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @param array : orderBy array('sort' => 'order', ..)
     * @return DoctrineCollection
     */
    public function getByUser($userId, $params = array(), $orderBy = array())
    {
        $q = $this->getByUserQuery($userId, $params, $orderBy);

        return is_null($q) ? array() : $q->getResult();
    }
}