<?php

namespace Sweetchuck\Robo\Bundler\Task;

class BundleExecTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'Bundler - Exec';

    /**
     * {@inheritdoc}
     */
    protected $action = 'exec';

    // region Option - cmdToExecute.
    /**
     * @var null|string|\Robo\Contract\CommandInterface
     */
    protected $cmdToExecute = null;

    public function getCmdToExecute()
    {
        return $this->cmdToExecute;
    }

    /**
     * @return $this
     */
    public function setCmdToExecute($value)
    {
        $this->cmdToExecute = $value;

        return $this;
    }
    // endregion

    /**
     * @return $this
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        foreach ($options as $name => $value) {
            switch ($name) {
                case 'cmdToExecute':
                    $this->setCmdToExecute($value);
                    break;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandOptions(): array
    {
        return [
            'cmdToExecute' => [
                'type' => 'as-is',
                'value' => $this->getCmdToExecute(),
            ],
        ] + parent::getCommandOptions();
    }
}
