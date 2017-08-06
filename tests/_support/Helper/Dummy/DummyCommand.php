<?php

namespace Sweetchuck\Robo\Bundler\Test\Helper\Dummy;

use Robo\Contract\CommandInterface;

class DummyCommand implements CommandInterface
{
    /**
     * @var string
     */
    protected $command = '';

    public function __construct(string $command = '')
    {
        $this->setCommand($command);
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return $this
     */
    public function setCommand(string $value)
    {
        $this->command = $value;

        return $this;
    }
}
