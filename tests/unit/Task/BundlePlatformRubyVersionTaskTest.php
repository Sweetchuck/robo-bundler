<?php

namespace Sweetchuck\Robo\Bundler\Tests\Unit\Task;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\Bundler\Task\BundlePlatformRubyVersionTask;
use Symfony\Component\Console\Output\OutputInterface;

class BundlePlatformRubyVersionTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Bundler\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle platform --ruby',
            ],
            'workingDirectory' => [
                "cd 'my-dir' && bundle platform --ruby",
                [
                    'workingDirectory' => 'my-dir',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand($expected, array $options = []): void
    {
        $task = new BundlePlatformRubyVersionTask();
        $task->setOptions($options);

        $this->tester->assertEquals($expected, $task->getCommand());
    }

    public function casesRun(): array
    {
        return [
            'plain' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'original' => '1.2.3',
                        'full' => '1.2.3',
                        'major' => 1,
                        'minor' => 2,
                        'patch' => 3,
                        'preReleaseVersion' => null,
                        'buildMetaData' => null,
                        'base' => '1.2.3',
                    ],
                ],
                [],
                [
                    'stdOutput' => "Ruby 1.2.3\n",
                ],
            ],
            'ruby style' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'original' => '1.2.3p45',
                        'full' => '1.2.3+p45',
                        'major' => 1,
                        'minor' => 2,
                        'patch' => 3,
                        'preReleaseVersion' => null,
                        'buildMetaData' => 'p45',
                        'base' => '1.2.3',
                    ],
                ],
                [],
                [
                    'stdOutput' => "Ruby 1.2.3p45\n",
                ],
            ],
            'No ruby version specified' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'original' => '',
                        'full' => '',
                    ],
                ],
                [],
                [
                    'stdOutput' => "No ruby version specified\n",
                ],
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
            'stdOutput' => '',
            'stdError' => '',
        ];

        $container = Robo::createDefaultContainer();
        Robo::setContainer($container);

        $outputConfig = [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
            'colors' => false,
        ];
        $mainStdOutput = new DummyOutput($outputConfig);

        /** @var \Sweetchuck\Robo\Bundler\Task\BundlePlatformRubyVersionTask $task */
        $task = Stub::construct(
            BundlePlatformRubyVersionTask::class,
            [],
            [
                'processClass' => DummyProcess::class,
            ]
        );
        $task->setOptions($options);

        $processIndex = count(DummyProcess::$instances);
        DummyProcess::$prophecy[$processIndex] = $processProphecy;

        $task->setLogger($container->get('logger'));
        $task->setOutput($mainStdOutput);

        $result = $task->run();

        $this->tester->assertSame(
            $expected['exitCode'],
            $result->getExitCode(),
            'Exit code is different than the expected.'
        );

        if (!empty($expected['assets'])) {
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertSame(
                    $assetValue,
                    $result[$assetName],
                    "Asset: $assetName"
                );
            }
        }
    }
}
