<?php

namespace Sweetchuck\Robo\Bundler\Tests\Acceptance\Task;

use Sweetchuck\Robo\Bundler\Test\AcceptanceTester;
use Sweetchuck\Robo\Bundler\Test\Helper\RoboFiles\BundleRoboFile;

class BundlePlatformRubyVersionCest
{
    public function runInstallSuccess(AcceptanceTester $I)
    {
        $id = 'bundle:platform:ruby-version';
        $I->runRoboTask($id, BundleRoboFile::class, 'bundle:platform:ruby-version');

        $exitCode = $I->getRoboTaskExitCode($id);
        $stdOutput = $I->getRoboTaskStdOutput($id);

        $I->assertEquals(0, $exitCode);
        $I->assertEquals(
            implode(PHP_EOL, [
                'original: 2.3.1p112',
                'full: 2.3.1+p112',
                'base: 2.3.1',
                'major: 2',
                'minor: 3',
                'patch: 1',
                'buildMetaData: p112',
                '',
            ]),
            $stdOutput
        );
    }
}
