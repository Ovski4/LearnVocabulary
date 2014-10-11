<?php

namespace Ovski\LanguageBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Gedmo\Translatable\TranslatableListener;

/**
 * WordTypeRepository
 */
class WordTypeRepository extends EntityRepository
{
    /**
     * Get default wordType value (english)
     *
     * @param int : the word type id
     * @return WordType
     */
    public function getDefaultWordTypeValue($id)
    {
        $qb = $this
            ->createQueryBuilder('wt')
            ->where('wt.id = :id')
            ->setParameter('id', $id)
        ;

        $query = $qb->getQuery();
        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'en');

        return $query->getSingleResult()->getValue();
    }
}