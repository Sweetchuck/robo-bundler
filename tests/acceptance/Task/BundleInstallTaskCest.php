<?php

namespace Cheppers\Robo\Bundler\Tests\Acceptance\Task;

use Cheppers\Robo\Bundler\Test\AcceptanceTester;
use Cheppers\Robo\Bundler\Test\Helper\RoboFiles\BundleRoboFile;

class BundleInstallTaskCest
{
    public function runInstallSuccess(AcceptanceTester $I)
    {
        $I->runRoboTask(BundleRoboFile::class, 'install:success');
        $I->assertContains(
            'Bundle complete! 1 Gemfile dependency',
            $I->getRoboTaskStdOutput()
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode());
    }
}
