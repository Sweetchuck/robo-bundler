<?php

namespace Sweetchuck\Robo\Bundler\Test\Helper\RoboFiles;

use Sweetchuck\Robo\Bundler\BundlerTaskLoader;
use Sweetchuck\Robo\Bundler\Test\Helper\Dummy\DummyCommand as DummyCommand;
use Robo\Contract\TaskInterface;
use Robo\State\Data as RoboStateData;
use Robo\Tasks;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class BundleRoboFile extends Tasks
{
    use BundlerTaskLoader;

    public function checkFail(): TaskInterface
    {
        return $this
            ->taskBundleCheck()
            ->setOutput($this->output())
            ->setGemFile($this->dataDir('Gemfile.fail.rb'));
    }

    public function execStringSuccess(): TaskInterface
    {
        return $this
            ->taskBundleExec()
            ->setOutput($this->output())
            ->setBundleGemFile($this->dataDir('Gemfile.success.rb'))
            ->setCmdToExecute('rdoc --help');
    }

    public function execCommandSuccess(): TaskInterface
    {
        return $this
            ->taskBundleExec()
            ->setOutput($this->output())
            ->setBundleGemFile($this->dataDir('Gemfile.success.rb'))
            ->setCmdToExecute(new DummyCommand('rdoc --help'));
    }

    public function installSuccess(): TaskInterface
    {
        return $this
            ->taskBundleInstall()
            ->setOutput($this->output())
            ->setGemFile($this->dataDir('Gemfile.success.rb'));
    }

    public function showPaths(): TaskInterface
    {
        return $this
            ->taskBundleShowPaths()
            ->setOutput($this->output())
            ->setBundleGemFile($this->dataDir('Gemfile.success.rb'));
    }

    /**
     * @command bundle:platform:ruby-version
     */
    public function bundlePlatformRubyVersion()
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskBundlePlatformRubyVersion()
                    ->setAssetNamePrefix('bundlePlatformRubyVersion.')
                    ->setBundleGemFile($this->dataDir('Gemfile.success.rb'))
            )
            ->addCode(function (RoboStateData $data) {
                $versionParts = [
                    'full' => $data['bundlePlatformRubyVersion.full'],
                    'base' => $data['bundlePlatformRubyVersion.base'],
                    'major' => $data['bundlePlatformRubyVersion.major'],
                    'minor' => $data['bundlePlatformRubyVersion.minor'],
                    'fix' => $data['bundlePlatformRubyVersion.fix'],
                    'patch' => $data['bundlePlatformRubyVersion.patch'],
                ];

                $this->output()->write(Yaml::dump($versionParts));

                return 0;
            });
    }

    protected function dataDir(string $suffix = ''): string
    {
        return Path::join(__DIR__, '../../../_data/', $suffix);
    }
}
