<?php

namespace Cheppers\Robo\Bundler\Test\Helper\RoboFiles;

use Cheppers\Robo\Bundler\BundlerTaskLoader;
use Robo\Contract\TaskInterface;
use Robo\Tasks;

class BundleRoboFile extends Tasks
{
    use BundlerTaskLoader;

    public function checkFail(): TaskInterface
    {
        return $this
            ->taskBundleCheck()
            ->setOutput($this->output())
            ->setGemFile(codecept_data_dir('Gemfile.fail.rb'));
    }

    public function installSuccess(): TaskInterface
    {
        return $this
            ->taskBundleInstall()
            ->setOutput($this->output())
            ->setGemFile(codecept_data_dir('Gemfile.success.rb'));
    }
}
