<?php

namespace Cheppers\Robo\Bundler\Option;

trait PathOption
{
    /**
     * @var string
     */
    protected $path = '';

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }
}
