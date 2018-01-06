<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class ApiFunctionalTest extends WebTestCase
{
    /**
     * @var Application
     */
    public static $application;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();

        self::$application = new Application(self::$kernel);
        self::$application->setAutoExit(false);

        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load --append');
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass()
    {
        self::runCommand('doctrine:database:drop --force');
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider authenticatedUrlProvider
     */
    public function testAuthPageIsSuccessful($url)
    {
        $jwt = $this->getJWT();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        $urlsWithLocal = array(
            '/',
            '/login',
            '/old-school',
            '/tips',
            '/contact',
            '/register/',
            '/resetting/request',
            '/_error/404',
            '/_error/500',
        );

        $urls = array(
            array('/'),
        );

        // Prefix the urls with a local by locals
        foreach ($urlsWithLocal as $urlWithLocal) {
            $urls[] = array('/fr' . $urlWithLocal);
            $urls[] = array('/en' . $urlWithLocal);
        }

        return $urls;

    }

    /**
     * Get the json web token
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function getJWT()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/login');
        $buttonCrawlerNode = $crawler->selectButton('Log in');
        $form = $buttonCrawlerNode->form();

        $data = array(
            '_username' => 'baptiste',
            '_password' => 'pwd'
        );

        $client->submit($form, $data);
        $client->followRedirect();

        return $client;
    }

    /**
     * Run a command
     *
     * @param $command
     */
    private static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        self::$application->run(new StringInput($command));
    }
}