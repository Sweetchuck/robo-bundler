<?php

namespace Cheppers\Robo\Bundler\Task;

class BundleShowPathsTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'BundleShowPaths';

    /**
     * {@inheritdoc}
     */
    protected $action = 'show';

    /**
     * {@inheritdoc}
     */
    protected function getCommandOptions(): array
    {
        return [
            'paths' => [
                'type' => 'tri-state',
                'value' => true,
            ],
        ] + parent::getCommandOptions();
    }

    /**
     * {@inheritdoc}
     */
    protected function runProcessOutputs()
    {
        if ($this->actionExitCode === 0) {
            $this->assets['paths'] = preg_split(
                "/\n+/",
                $this->actionStdOutput,
                -1,
                PREG_SPLIT_NO_EMPTY
            );
        } else {
            $this->assets['paths'] = [];
        }

        return $this;
    }
}
