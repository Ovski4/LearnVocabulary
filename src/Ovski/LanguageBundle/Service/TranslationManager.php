<?php

namespace Ovski\LanguageBundle\Service;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TranslationManager
{
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Generate a csv file for a given learning and user
     *
     * @param $userId
     * @param $learningSlug
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function generateCsv($userId, $learningSlug) {
        $learning = $this->em->getRepository('OvskiLanguageBundle:Learning')->getOneByUser(
            $userId,
            array('slug' => $learningSlug)
        );

        if (!$learning) {
            throw new NotFoundHttpException(sprintf("Learning %s could not be found", $slug));
        }

        $translations = $this->em->getRepository('OvskiLanguageBundle:Translation')->findBy(
            array(
                "learning" => $learning->getId(),
                "user" => $userId,
            ),
            array("createdAt" => 'DESC')
        );

        $translationArray = array();
        $date = new \DateTime('now');

        $file = fopen('php://memory', 'r+');

        $headeline = array(
            'id',
            'is starred',
            sprintf('%s article', $learning->getLanguage1()),
            sprintf('%s word', $learning->getLanguage1()),
            sprintf('%s article', $learning->getLanguage2()),
            sprintf('%s word', $learning->getLanguage2()),
            'word type',
            'created at'
        );

        fputcsv($file, $headeline);

        foreach ($translations as $translation) {
            $line = array(
                $translation->getId(),
                $translation->getIsStarred(),
                $translation->getWord1()->getArticle(),
                $translation->getWord1(),
                $translation->getWord2()->getArticle(),
                $translation->getWord2(),
                $translation->getWordType(),
                $translation->getCreatedAt()->format('Y-m-d') //TODO according to locale
            );
            fputcsv($file, $line);
        }
        rewind($file);
        $content = stream_get_contents($file);
        fclose($file);

        return $content;
    }

    public function importCsv($userId, $learningSlug, $csv) {
        $learning = $this->em->getRepository('OvskiLanguageBundle:Learning')->getOneByUser(
            $userId,
            array('slug' => $learningSlug)
        );

        if (!$learning) {
            throw new NotFoundHttpException(sprintf("Learning %s could not be found", $slug));
        }

        if (($handle = fopen($csv, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000)) !== FALSE) {
                
            }
            fclose($handle);
        }
    }
} 