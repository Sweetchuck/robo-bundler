<?php

namespace Cheppers\Robo\Bundler\Tests\Acceptance\Task;

use Cheppers\Robo\Bundler\Test\AcceptanceTester;
use Cheppers\Robo\Bundler\Test\Helper\RoboFiles\BundleRoboFile;

class BundleCheckTaskCest
{
    public function runCheckFail(AcceptanceTester $I)
    {
        $I->runRoboTask(BundleRoboFile::class, 'check:fail');
        $I->assertContains(
            "Bundler can't satisfy your Gemfile's dependencies.",
            $I->getRoboTaskStdOutput()
        );
        $I->assertEquals(1, $I->getRoboTaskExitCode());
    }
}
