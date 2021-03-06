<?php

namespace Sweetchuck\Robo\Bundler\Tests\Acceptance\Task;

use Sweetchuck\Robo\Bundler\Test\AcceptanceTester;
use Sweetchuck\Robo\Bundler\Test\Helper\RoboFiles\BundleRoboFile;

class BundleInstallTaskCest
{
    public function runInstallSuccess(AcceptanceTester $I)
    {
        $id = 'install:success';
        $I->runRoboTask($id, BundleRoboFile::class, 'install:success');
        $I->assertContains(
            'Bundle complete! 1 Gemfile dependency',
            $I->getRoboTaskStdOutput($id)
        );
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
    }
}
