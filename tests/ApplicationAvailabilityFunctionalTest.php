<?php

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
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
        $client = $this->logIn();
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

    public function authenticatedUrlProvider()
    {
        $urlsWithLocal = array(
            '/languages/',
            '/languages/new',
            '/revision/french-spanish',
            '/edition/french-spanish',
            '/edition/french-spanish/1/edit',
            '/settings/',
            '/profile/',
            '/profile/edit',
            '/profile/change-password',
        );

        $urls = array();

        // Prefix the urls with a local by locals
        foreach ($urlsWithLocal as $urlWithLocal) {
            $urls[] = array('/fr' . $urlWithLocal);
            $urls[] = array('/en' . $urlWithLocal);
        }

        return $urls;
    }

    /**
     * Log a user in
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function logIn()
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