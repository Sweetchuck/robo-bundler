<?php

namespace Cheppers\Robo\Bundler\Task;

use Cheppers\Robo\Bundler\Option\PathOption;

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
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();

        $this->options += [
            'path' => 'value',
        ];

        return $this;
    }

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
        return parent::getCommandOptions() + [
            'path' => $this->getPath(),
        ];
    }
}
