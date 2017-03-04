<?php

namespace Cheppers\Robo\Bundler\Tests\Unit\Task;

use Cheppers\Robo\Bundler\Task\BundleExecTask;
use Cheppers\Robo\Bundler\Test\Helper\Dummy\Command as DummyCommand;
use Codeception\Test\Unit;

class BundleExecTaskTest extends Unit
{
    /**
     * @var \Cheppers\Robo\Bundler\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle exec',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && bundle exec",
                [
                    'workingDirectory' => 'my-dir',
                ],
            ],
            'workingDirectory with ENV' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='../myGemfile' bundle exec",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => '../myGemfile',
                ],
            ],
            'bundleExecutable' => [
                'my-bundle exec',
                [
                    'bundleExecutable' => 'my-bundle',
                ],
            ],
            'as-is string' => [
                'bundle exec compass compile',
                [
                    'cmdToExecute' => 'compass compile',
                ],
            ],
            'as-is CommandInterface' => [
                'bundle exec compass compile --environment production',
                [
                    'cmdToExecute' => new DummyCommand('compass compile --environment production'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new BundleExecTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }
}
