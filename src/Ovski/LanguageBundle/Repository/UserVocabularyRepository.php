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
     * @return QueryBuilder
     */
    public function getByUserQueryBuilder($userId, $params = array())
    {
        $qb = $this->createQueryBuilder('l');
        $qb
            ->join('l.users', 'u')
            ->where($qb->expr()->eq('u.id', $userId))
        ;
        foreach ($params as $key => $value) {
            $qb
                ->andWhere(sprintf('l.%s = :param'.$key, $key))
                ->setParameter('param'.$key, $value)
            ;
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
     * @return Query
     */
    public function getByUserQuery($userId, $params = array())
    {
        $qb = $this->getByUserQueryBuilder($userId, $params);

        return is_null($qb) ? $qb : $qb->getQuery();
    }

    /**
     * getOneByUser
     *
     * Get one by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @return DoctrineCollection
     */
    public function getOneByUser($userId, $params = array())
    {
        $q = $this->getByUserQuery($userId, $params);

        return is_null($q) ? array() : $q->getSingleResult();
    }

    /**
     * getOneOrNullByUser
     *
     * Get one by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @return DoctrineCollection
     */
    public function getOneOrNullByUser($userId, $params = array())
    {
        $q = $this->getByUserQuery($userId, $params);

        return is_null($q) ? array() : $q->getOneOrNullResult();
    }

    /**
     * getByUser
     *
     * Get by user
     *
     * @param string : the user id
     * @param array : params array('param_name' => 'value', ..)
     * @return DoctrineCollection
     */
    public function getByUser($userId, $params = array())
    {
        $q = $this->getByUserQuery($userId, $params);

        return is_null($q) ? array() : $q->getResult();
    }
}