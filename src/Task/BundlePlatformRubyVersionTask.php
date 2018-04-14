<?php

namespace Sweetchuck\Robo\Bundler\Task;

use Icecave\SemVer\Version as SemVerVersion;

class BundlePlatformRubyVersionTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Bundler - Platform - Ruby version';

    /**
     * {@inheritdoc}
     */
    protected $action = 'platform';

    /**
     * {@inheritdoc}
     */
    protected function getCommandOptions(): array
    {
        return [
            'ruby' => [
                'type' => 'flag',
                'value' => true,
            ],
        ] + parent::getCommandOptions();
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        parent::runProcessOutputs();
        $this->assets['original'] = '';
        $this->assets['full'] = '';

        if ($this->actionExitCode === 0) {
            list(, $versionOriginal) = explode(' ', trim($this->actionStdOutput), 2) + [1 => ''];
            $versionFull = $this->convertRubyVersionPatchLevelToSemVerBuildMetaData($versionOriginal);
            if (!SemVerVersion::isValid($versionFull)) {
                // No ruby version specified.
                return $this;
            }

            $version = SemVerVersion::parse($versionFull);
            $this->assets['original'] = $versionOriginal;
            $this->assets['full'] = $versionFull;
            $this->assets['semVerVersion'] = $version;
            $this->assets['major'] = $version->major();
            $this->assets['minor'] = $version->minor();
            $this->assets['patch'] = $version->patch();
            $this->assets['preReleaseVersion'] = $version->preReleaseVersion();
            $this->assets['buildMetaData'] = $version->buildMetaData();
            $this->assets['base'] = sprintf('%d.%d.%d', $version->major(), $version->minor(), $version->patch());
        }

        return $this;
    }

    protected function convertRubyVersionPatchLevelToSemVerBuildMetaData(string $rubyVersion): string
    {
        $matches = [];
        $pattern = '/^(?P<version>\d+\.\d+\.\d+)(?P<patch>p\d+)$/';
        if (preg_match($pattern, $rubyVersion, $matches)) {
            $rubyVersion = $matches['version'] . '+' . $matches['patch'];
        }

        return $rubyVersion;
    }
}
