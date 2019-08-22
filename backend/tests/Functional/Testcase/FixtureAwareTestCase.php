<?php

namespace App\Tests\Functional\Testcase;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extend this class to execute tests on a new clean database filled with fixtures
 */
class FixtureAwareTestCase extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;
    /** @var  Client $client */
    protected $client;
    /** @var  ContainerInterface $container */
    protected static $container;
    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->client = parent::createClient();
        self::$container = $this->client->getContainer();
        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');

        // Make sure we are in the test environment
        if (self::$container->get('kernel')->getEnvironment() !== 'test') {
            throw new \LogicException('Primer must be executed in the test environment');
        }
    }

    /**
     * Creates the database, clears the cache, runs migrations and loads fixtures
     *
     * {@inheritdoc}
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load --append');
    }

    /**
     * Runs a symfony application (bin/console) command with the flags -v -n --env=test
     *
     * @param string $command
     * @return int
     * @throws \RuntimeException
     * @throws \Exception
     */
    protected static function runCommand(string $command)
    {
        echo "\r\n" . $command . "\r\n";
        $command = sprintf('%s -n -v --env=test', $command);

        $result = self::getApplication()->run(new StringInput($command));

        return $result;
    }

    /**
     * @return Application
     */
    protected static function getApplication()
    {
        $client = parent::createClient();

        self::$application = new Application($client->getKernel());
        self::$application->setAutoExit(false);

        return self::$application;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        self::runCommand('doctrine:database:drop --force');
    }
}
