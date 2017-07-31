<?php

namespace Sweetchuck\Robo\Bundler\Tests\Unit\Task;

use Sweetchuck\Robo\Bundler\Task\BundleInstallTask;
use Codeception\Test\Unit;

class BundleInstallTaskTest extends Unit
{
    /**
     * @var \Sweetchuck\Robo\Bundler\Test\UnitTester
     */
    protected $tester;

    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'bundle install',
                [],
            ],
            'workingDirectory' => [
                "cd 'my-dir' && bundle install",
                [
                    'workingDirectory' => 'my-dir',
                ],
            ],
            'workingDirectory with ENV' => [
                "cd 'my-dir' && BUNDLE_GEMFILE='../myGemfile' bundle install",
                [
                    'workingDirectory' => 'my-dir',
                    'bundleGemFile' => '../myGemfile',
                ],
            ],
            'gemFile' => [
                "bundle install --gemfile='myGemfile'",
                [
                    'gemFile' => 'myGemfile',
                ],
            ],
            'bundleExecutable' => [
                'my-bundle install',
                [
                    'bundleExecutable' => 'my-bundle',
                ],
            ],
            'verbose true' => [
                'bundle install --verbose',
                [
                    'verbose' => true,
                ],
            ],
            'verbose false' => [
                'bundle install --no-verbose',
                [
                    'verbose' => false,
                ],
            ],
            'noColor true' => [
                'bundle install --no-color',
                [
                    'noColor' => true,
                ],
            ],
            'noColor false' => [
                'bundle install --no-no-color',
                [
                    'noColor' => false,
                ],
            ],
            'binStubs null' => [
                'bundle install',
                [
                    'binStubs' => null,
                ],
            ],
            'binStubs empty' => [
                'bundle install --binstubs',
                [
                    'binStubs' => '',
                ],
            ],
            'binstubs value' => [
                "bundle install --binstubs='foo'",
                [
                    'binStubs' => 'foo',
                ],
            ],
            'clean true' => [
                'bundle install --clean',
                [
                    'clean' => true,
                ],
            ],
            'fullIndex true' => [
                'bundle install --full-index',
                [
                    'fullIndex' => true,
                ],
            ],
            'jobs 0' => [
                'bundle install',
                [
                    'jobs' => 0,
                ],
            ],
            'jobs 42' => [
                "bundle install --jobs='42'",
                [
                    'jobs' => 42,
                ],
            ],
            'local true' => [
                'bundle install --local',
                [
                    'local' => true,
                ],
            ],
            'deployment true' => [
                'bundle install --deployment',
                [
                    'deployment' => true,
                ],
            ],
            'force true' => [
                'bundle install --force',
                [
                    'force' => true,
                ],
            ],
            'frozen true' => [
                'bundle install --frozen',
                [
                    'frozen' => true,
                ],
            ],
            'system true' => [
                'bundle install --system',
                [
                    'system' => true,
                ],
            ],
            'noCache true' => [
                'bundle install --no-cache',
                [
                    'noCache' => true,
                ],
            ],
            'noPrune true' => [
                'bundle install --no-prune',
                [
                    'noPrune' => true,
                ],
            ],
            'path value' => [
                "bundle install --path='my-path'",
                [
                    'path' => 'my-path',
                ],
            ],
            'quiet true' => [
                'bundle install --quiet',
                [
                    'quiet' => true,
                ],
            ],
            'retry 0' => [
                'bundle install',
                [
                    'retry' => 0,
                ],
            ],
            'retry 42' => [
                "bundle install --retry='42'",
                [
                    'retry' => 42,
                ],
            ],
            'shebang empty' => [
                'bundle install',
                [
                    'shebang' => '',
                ],
            ],
            'shebang value' => [
                "bundle install --shebang='ruby'",
                [
                    'shebang' => 'ruby',
                ],
            ],
            'standalone items' => [
                "bundle install --standalone='a b c'",
                [
                    'standalone' => ['a', 'b', 'c'],
                ],
            ],
            'standalone bool' => [
                "bundle install --standalone='a b d'",
                [
                    'standalone' => [
                        'a' => true,
                        'b' => true,
                        'c' => false,
                        'd' => true,
                    ],
                ],
            ],
            'trustPolicy empty' => [
                'bundle install',
                [
                    'trustPolicy' => '',
                ],
            ],
            'trustPolicy value' => [
                "bundle install --trust-policy='NoSecurity'",
                [
                    'trustPolicy' => 'NoSecurity',
                ],
            ],
            'with & without 1' => [
                "bundle install --with='a b' --without='d e'",
                [
                    'withOrWithout' => [
                        'a' => true,
                        'b' => true,
                        'c' => null,
                        'd' => false,
                        'e' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $task = new BundleInstallTask($options);
        $this->tester->assertEquals($expected, $task->getCommand());
    }
}
