<?php

namespace Sweetchuck\Robo\Bundler\Tests\Unit\Task;

use Sweetchuck\Robo\Bundler\Task\BundleCheckTask;
use Codeception\Test\Unit;

class BundleCheckTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Bundler\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle check',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && bundle check",
                [
                    'workingDirectory' => 'my-dir',
                ],
            ],
            'workingDirectory with ENV' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='../myGemfile' bundle check",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => '../myGemfile',
                ],
            ],
            'gemFile' => [
                "bundle check --gemfile='myGemfile'",
                [
                    'gemFile' => 'myGemfile',
                ],
            ],
            'bundleExecutable' => [
                'my-bundle check',
                [
                    'bundleExecutable' => 'my-bundle',
                ],
            ],
            'path value' => [
                "bundle check --path='my-path'",
                [
                    'path' => 'my-path',
                ],
            ],
            'verbose true' => [
                'bundle check --verbose',
                [
                    'verbose' => true,
                ],
            ],
            'verbose false' => [
                'bundle check --no-verbose',
                [
                    'verbose' => false,
                ],
            ],
            'no-color true' => [
                'bundle check --no-color',
                [
                    'noColor' => true,
                ],
            ],
            'no-color false' => [
                'bundle check --no-no-color',
                [
                    'noColor' => false,
                ],
            ],
            'common ones' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='Gemfile.my.rb' bundle check --verbose",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => 'Gemfile.my.rb',
                    'verbose' => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new BundleCheckTask();
        $task->setOptions($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }
}
