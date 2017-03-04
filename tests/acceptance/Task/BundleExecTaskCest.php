<?php

namespace Cheppers\Robo\Bundler\Tests\Acceptance\Task;

use Cheppers\Robo\Bundler\Test\AcceptanceTester;
use Cheppers\Robo\Bundler\Test\Helper\RoboFiles\BundleRoboFile;

class BundleExecTaskCest
{
    public function runExecStringSuccess(AcceptanceTester $I)
    {
        $id = 'exec:string-success';
        $I->runRoboTask($id, BundleRoboFile::class, 'exec:string-success');
        $I->assertContains('Usage: rdoc [options] [names...]', $I->getRoboTaskStdOutput($id));
        $I->assertContains('bundle exec rdoc --help', $I->getRoboTaskStdError($id));
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
    }

    public function runExecCommandSuccess(AcceptanceTester $I)
    {
        $id = 'exec:command-success';
        $I->runRoboTask($id, BundleRoboFile::class, 'exec:command-success');
        $I->assertContains('Usage: rdoc [options] [names...]', $I->getRoboTaskStdOutput($id));
        $I->assertContains('bundle exec rdoc --help', $I->getRoboTaskStdError($id));
        $I->assertEquals(0, $I->getRoboTaskExitCode($id));
    }
}
