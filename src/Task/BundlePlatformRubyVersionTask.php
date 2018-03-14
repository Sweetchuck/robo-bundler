<?php

namespace Sweetchuck\Robo\Bundler\Task;

use Sweetchuck\Robo\Bundler\Utils;

class BundlePlatformRubyVersionTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'BundlePlatformRubyVersion';

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
        $this->assets['full'] = '';
        if ($this->actionExitCode === 0) {
            $parts = explode(' ', trim($this->actionStdOutput), 2) + [1 => ''];
            $rubyVersion = Utils::parseRubyVersion($parts[1]);
            foreach ($rubyVersion as $key => $value) {
                $this->assets[$key] = $value;
            }
        }

        return $this;
    }
}
