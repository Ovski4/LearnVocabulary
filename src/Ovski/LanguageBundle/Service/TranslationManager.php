<?php

namespace Ovski\LanguageBundle\Service;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ovski\LanguageBundle\Entity\Word;
use Ovski\LanguageBundle\Entity\Translation;
use Ovski\LanguageBundle\Entity\Article;

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

    public function importCsv($user, $learningSlug, $csv) {
        $learning = $this->em->getRepository('OvskiLanguageBundle:Learning')->getOneByUser(
            $user->getId(),
            array('slug' => $learningSlug)
        );

        if (!$learning) {
            throw new NotFoundHttpException(sprintf("Learning %s could not be found", $slug));
        }

        // Get languages
        if (($handle = fopen($csv, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000)) !== FALSE) {
                $language1Name = substr($data[3], 0, strpos($data[3], ' word'));
                $language2Name = substr($data[5], 0, strpos($data[3], ' word'));
                break;
            }
            fclose($handle);
        }

        $language1 = $this->em->getRepository('OvskiLanguageBundle:Language')->findOneByName($language1Name);
        $language2 = $this->em->getRepository('OvskiLanguageBundle:Language')->findOneByName($language2Name);

        // insert translations
        if (($handle = fopen($csv, "r")) !== FALSE) {
            $cpt = 0;
            while (($data = fgetcsv($handle, 1000)) !== FALSE) {
                $cpt++;
                if ($cpt != 1) {
                    $wordType = $this->em->getRepository('OvskiLanguageBundle:WordType')->findOneByValue($data[6]);
                    $article1 = $this->em->getRepository('OvskiLanguageBundle:Article')->findOneBy(array(
                        'language' => $language1,
                        'value'    => $data[2]
                    ));
                    $word1 = new Word();
                    $word1->setWordType($wordType);
                    $word1->setValue($data[3]);
                    $word1->setArticle($article1);
                    $word1->setLanguage($language1);
                    $word1 = $this->getWordIfExists($word1);
                    $article2 = $this->em->getRepository('OvskiLanguageBundle:Article')->findOneBy(array(
                        'language' => $language2,
                        'value'    => $data[4]
                    ));
                    $word2 = new Word();
                    $word2->setWordType($wordType);
                    $word2->setValue($data[5]);
                    $word2->setArticle($article2);
                    $word2->setLanguage($language2);
                    $word2 = $this->getWordIfExists($word2);
                    $translation = new Translation();
                    $translation->setWordType($wordType);
                    $translation->setLearning($learning);
                    $translation->setUser($user);
                    $translation->setWord1($word1);
                    $translation->setWord2($word2);
                    $this->em->persist($translation);
                }
            }
            fclose($handle);
        }
echo "FLUSH";
        $this->em->flush();
    }

    /**
     * Check whether or not a word already exists
     *
     * @param Word $word
     * @return mixed
     */
    private function getWordIfExists(Word $word) {

        $existingWord = $this->em->getRepository('OvskiLanguageBundle:Word')->findOneBy(
            array(
                'article'  => $word->getArticle(),
                'wordType' => $word->getWordType(),
                'language' => $word->getLanguage(),
                'value'    => $word->getValue()
            )
        );

        if (!$existingWord) {
            return $word;
        }
        return $existingWord;
    }
} 