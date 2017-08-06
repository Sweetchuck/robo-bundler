<?php

namespace Sweetchuck\Robo\Bundler\Tests\Unit\Task;

use Sweetchuck\Robo\Bundler\Task\BundleShowPathsTask;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Robo\Bundler\Test\Helper\Dummy\DummyProcess;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Robo\Robo;
use Symfony\Component\Console\Output\OutputInterface;

class BundleShowPathsTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Bundler\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle show --paths',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && bundle show --paths",
                [
                    'workingDirectory' => 'my-dir',
                ],
            ],
            'workingDirectory with ENV' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='../myGemfile' bundle show --paths",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => '../myGemfile',
                ],
            ],
            'gemFile' => [
                "bundle show --paths --gemfile='myGemfile'",
                [
                    'gemFile' => 'myGemfile',
                ],
            ],
            'bundleExecutable' => [
                'my-bundle show --paths',
                [
                    'bundleExecutable' => 'my-bundle',
                ],
            ],
            'no-color true' => [
                'bundle show --paths --no-color',
                [
                    'noColor' => true,
                ],
            ],
            'no-color false' => [
                'bundle show --paths --no-no-color',
                [
                    'noColor' => false,
                ],
            ],
            'common ones' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='Gemfile.my.rb' bundle show --paths",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => 'Gemfile.my.rb',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new BundleShowPathsTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function casesRun(): array
    {
        return [
            'success' => [
                [
                    'exitCode' => 0,
                    'paths' => [
                        '/a/b/c-1.0.0',
                        '/d/e/f-2.0.0',
                    ],
                ],
                ['workingDirectory' => 'success'],
            ],
            'fail' => [
                [
                    'exitCode' => 1,
                    'paths' => [],
                ],
                ['workingDirectory' => 'fail'],
            ],
        ];
    }

    /**
     * @dataProvider casesRun
     */
    public function testRun(array $expected, array $options = [], array $processProphecy = []): void
    {
        $processProphecy += [
            'exitCode' => $expected['exitCode'],
            'stdOutput' => implode("\n", $expected['paths']) . "\n",
            'stdError' => '',
        ];

        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $outputConfig = [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
            'colors' => false,
        ];
        $mainStdOutput = new DummyOutput($outputConfig);

        /** @var \Sweetchuck\Robo\Bundler\Task\BundleShowPathsTask $task */
        $task = Stub::construct(
            BundleShowPathsTask::class,
            [$options],
            [
                'processClass' => DummyProcess::class,
            ]
        );

        $processIndex = count(DummyProcess::$instances);
        DummyProcess::$prophecy[$processIndex] = $processProphecy;

        $task->setLogger($container->get('logger'));
        $task->setOutput($mainStdOutput);

        $result = $task->run();

        $this->tester->assertEquals(
            $expected['exitCode'],
            $result->getExitCode(),
            'Exit code is different than the expected.'
        );

        $this->tester->assertEquals(
            $expected['paths'],
            $result['paths'],
            'Result content: paths'
        );
    }
}
