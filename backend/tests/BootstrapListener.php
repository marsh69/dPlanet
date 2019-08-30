<?php

namespace App\Tests;

use App\Kernel;
use Exception;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class BootstrapListener implements TestListener
{
    const DATABASE_TEST_SUITES = ['functional'];

    /**
     * @param TestSuite $suite
     * @throws Exception
     */
    public function startTestSuite(TestSuite $suite): void
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

    /**
     * An error occurred.
     *
     * @param Test $test
     * @param \Throwable $e
     * @param float $time
     */
    public function addError(Test $test, \Throwable $e, $time): void
    {
    }

    /**
     * A warning occurred.
     *
     * @param Test $test
     * @param Warning $e
     * @param float $time
     */
    public function addWarning(Test $test, Warning $e, $time): void
    {
    }

    /**
     * A failure occurred.
     *
     * @param Test $test
     * @param AssertionFailedError $e
     * @param float $time
     */
    public function addFailure(Test $test, AssertionFailedError $e, $time): void
    {
    }

    /**
     * Incomplete test.
     *
     * @param Test $test
     * @param \Throwable $e
     * @param float $time
     */
    public function addIncompleteTest(Test $test, \Throwable $e, $time): void
    {
    }

    /**
     * Risky test.
     *
     * @param Test $test
     * @param \Throwable $e
     * @param float $time
     */
    public function addRiskyTest(Test $test, \Throwable $e, $time): void
    {
    }

    /**
     * Skipped test.
     *
     * @param Test $test
     * @param \Throwable $e
     * @param float $time
     */
    public function addSkippedTest(Test $test, \Throwable $e, $time): void
    {
    }

    /**
     * A test suite ended.
     *
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite): void
    {
    }

    /**
     * A test started.
     *
     * @param Test $test
     */
    public function startTest(Test $test): void
    {
    }

    /**
     * A test ended.
     *
     * @param Test $test
     * @param float $time
     */
    public function endTest(Test $test, $time): void
    {
    }
}
