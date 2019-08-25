<?php

namespace App\Tests;

use App\Kernel;
use Exception;
use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\TestSuite;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class BootstrapListener extends BaseTestListener
{
    const DATABASE_TEST_SUITES = ['functional'];

    /**
     * @param TestSuite $suite
     * @throws Exception
     */
    public function startTestSuite(TestSuite $suite)
    {
        if (in_array($suite->getName(), self::DATABASE_TEST_SUITES)) {
            $this->setUpTestDatabase();
        }
    }

    /**
     * @throws Exception
     */
    protected function setUpTestDatabase(): void
    {
        $kernel = new Kernel('test', false);
        $kernel->boot();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        foreach ($this->getBootstrapCommands() as $command) {
            $application->run(new StringInput("$command --env=test"));
        }
    }

    /**
     * @return array
     */
    protected function getBootstrapCommands(): array
    {
        return [
            'doctrine:cache:clear-metadata',
            'doctrine:database:drop --force',
            'doctrine:database:create',
            'doctrine:schema:update --force',
            'doctrine:fixtures:load -n',
        ];
    }
}
