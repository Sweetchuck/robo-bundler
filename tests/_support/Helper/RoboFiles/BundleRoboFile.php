<?php

namespace Cheppers\Robo\Bundler\Test\Helper\RoboFiles;

use Cheppers\Robo\Bundler\BundlerTaskLoader;
use Cheppers\Robo\Bundler\Test\Helper\Dummy\Command as DummyCommand;
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

    public function execStringSuccess(): TaskInterface
    {
        return $this
            ->taskBundleExec()
            ->setOutput($this->output())
            ->setBundleGemFile(codecept_data_dir('Gemfile.success.rb'))
            ->setCmdToExecute('rdoc --help');
    }

    public function execCommandSuccess(): TaskInterface
    {
        return $this
            ->taskBundleExec()
            ->setOutput($this->output())
            ->setBundleGemFile(codecept_data_dir('Gemfile.success.rb'))
            ->setCmdToExecute(new DummyCommand('rdoc --help'));
    }

    public function installSuccess(): TaskInterface
    {
        return $this
            ->taskBundleInstall()
            ->setOutput($this->output())
            ->setGemFile(codecept_data_dir('Gemfile.success.rb'));
    }

    public function showPaths(): TaskInterface
    {
        return $this
            ->taskBundleShowPaths()
            ->setOutput($this->output())
            ->setBundleGemFile(codecept_data_dir('Gemfile.success.rb'));
    }
}
