<?php

namespace Sweetchuck\Robo\Bundler\Task;

use Sweetchuck\Robo\Bundler\Option\PathOption;

class BundleCheckTask extends BaseTask
{
    use PathOption;

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'BundleCheck';

    /**
     * {@inheritdoc}
     */
    protected $action = 'check';

    /**
     * @return $this
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        foreach ($options as $name => $value) {
            switch ($name) {
                case 'path':
                    $this->setPath($value);
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
            'path' => [
                'type' => 'value',
                'value' => $this->getPath(),
            ],
        ] + parent::getCommandOptions();
    }
}
