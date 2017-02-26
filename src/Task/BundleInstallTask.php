<?php

namespace Cheppers\Robo\Bundler\Task;

use Cheppers\Robo\Bundler\Option\PathOption;

class BundleInstallTask extends BaseTask
{
    use PathOption;

    /**
     * {@inheritdoc}
     */
    protected $taskName = 'BundleInstall';

    /**
     * {@inheritdoc}
     */
    protected $action = 'install';

    //region Options.

    //region Option - binStubs.
    /**
     * @var null|string
     */
    protected $binStubs = null;

    public function getBinStubs(): ?string
    {
        return $this->binStubs;
    }

    /**
     * @return $this
     */
    public function setBinStubs(?string $value)
    {
        $this->binStubs = $value;

        return $this;
    }
    //endregion

    //region Option - clean.
    /**
     * @var bool
     */
    protected $clean = false;

    public function getClean(): bool
    {
        return $this->clean;
    }

    /**
     * @return $this
     */
    public function setClean(bool $value)
    {
        $this->clean = $value;

        return $this;
    }
    //endregion

    //region Option - fullIndex.
    /**
     * @var bool
     */
    protected $fullIndex = false;

    public function getFullIndex(): bool
    {
        return $this->fullIndex;
    }

    /**
     * @return $this
     */
    public function setFullIndex(bool $value)
    {
        $this->fullIndex = $value;

        return $this;
    }
    //endregion

    //region Option - jobs.
    /**
     * @var int
     */
    protected $jobs = 0;

    public function getJobs(): int
    {
        return $this->jobs;
    }

    /**
     * @return $this
     */
    public function setJobs(int $value)
    {
        $this->jobs = $value;

        return $this;
    }
    //endregion

    //region Option - local.
    /**
     * @var bool
     */
    protected $local = false;

    public function getLocal(): bool
    {
        return $this->local;
    }

    /**
     * @return $this
     */
    public function setLocal(bool $value)
    {
        $this->local = $value;

        return $this;
    }
    //endregion

    //region Option - deployment.
    /**
     * @var bool
     */
    protected $deployment = false;

    public function getDeployment(): bool
    {
        return $this->deployment;
    }

    /**
     * @return $this
     */
    public function setDeployment(bool $value)
    {
        $this->deployment = $value;

        return $this;
    }
    //endregion

    //region Option - force.
    /**
     * @var bool
     */
    protected $force = false;

    public function getForce(): bool
    {
        return $this->force;
    }

    /**
     * @return $this
     */
    public function setForce(bool $value)
    {
        $this->force = $value;

        return $this;
    }
    //endregion

    //region Option - frozen.
    /**
     * @var bool
     */
    protected $frozen = false;

    public function getFrozen(): bool
    {
        return $this->frozen;
    }

    /**
     * @return $this
     */
    public function setFrozen(bool $value)
    {
        $this->frozen = $value;

        return $this;
    }
    //endregion

    //region Option - system.
    /**
     * @var bool
     */
    protected $system = false;

    public function getSystem(): bool
    {
        return $this->system;
    }

    /**
     * @return $this
     */
    public function setSystem(bool $value)
    {
        $this->system = $value;

        return $this;
    }
    //endregion

    //region Option - noCache.
    /**
     * @var bool
     */
    protected $noCache = false;

    public function getNoCache(): bool
    {
        return $this->noCache;
    }

    /**
     * @return $this
     */
    public function setNoCache(bool $value)
    {
        $this->noCache = $value;

        return $this;
    }
    //endregion

    //region Option - noPrune.
    /**
     * @var bool
     */
    protected $noPrune = false;

    public function getNoPrune(): bool
    {
        return $this->noPrune;
    }

    /**
     * @return $this
     */
    public function setNoPrune(bool $value)
    {
        $this->noPrune = $value;

        return $this;
    }
    //endregion

    //region Option - quiet.
    /**
     * @var bool
     */
    protected $quiet = false;

    public function getQuiet(): bool
    {
        return $this->quiet;
    }

    /**
     * @return $this
     */
    public function setQuiet(bool $value)
    {
        $this->quiet = $value;

        return $this;
    }
    //endregion

    //region Option - retry.
    /**
     * @var int
     */
    protected $retry = 0;

    public function getRetry(): int
    {
        return $this->retry;
    }

    /**
     * @return $this
     */
    public function setRetry(int $value)
    {
        $this->retry = $value;

        return $this;
    }
    //endregion

    //region Option - shebang.
    /**
     * @var string
     */
    protected $shebang = '';

    public function getShebang(): string
    {
        return $this->shebang;
    }

    /**
     * @return $this
     */
    public function setShebang(string $value)
    {
        $this->shebang = $value;

        return $this;
    }
    //endregion

    //region Option - standalone.
    /**
     * @var array
     */
    protected $standalone = [];

    public function getStandalone(): array
    {
        return $this->standalone;
    }

    /**
     * @return $this
     */
    public function setStandalone(array $value)
    {
        $this->standalone = $value;

        return $this;
    }

    public function addStandalone(string $name)
    {
        $this->standalone[$name] = true;

        return $this;
    }

    public function removeStandalone(string $name)
    {
        $this->standalone[$name] = false;

        return $this;
    }
    //endregion

    //region Option - trustPolicy.
    /**
     * @var string
     */
    protected $trustPolicy = '';

    public function getTrustPolicy(): string
    {
        return $this->trustPolicy;
    }

    /**
     * @return $this
     */
    public function setTrustPolicy(string $value)
    {
        $this->trustPolicy = $value;

        return $this;
    }
    //endregion

    //region Option - with & without.
    /**
     * @var bool[]
     */
    protected $withOrWithout = [];

    public function getWithOrWithout(): array
    {
        return $this->withOrWithout;
    }

    /**
     * @return $this
     */
    public function setWithOrWithout(array $value)
    {
        $this->withOrWithout = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function addWithOrWithout(string $name, ?bool $value)
    {
        $this->withOrWithout[$name] = $value;

        return $this;
    }
    //endregion
    //endregion

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();

        $this->options += [
            'binstubs' => 'value-optional',
            'clean' => 'flag',
            'full-index' => 'flag',
            'jobs' => 'value',
            'local' => 'flag',
            'deployment' => 'flag',
            'force' => 'flag',
            'frozen' => 'flag',
            'system' => 'flag',
            'no-cache' => 'flag',
            'no-prune' => 'flag',
            'path' => 'value',
            'quiet' => 'flag',
            'retry' => 'value',
            'shebang' => 'value',
            'standalone' => 'space-separated',
            'trust-policy' => 'value',
            'with|without' => 'true|false',
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $option)
    {
        parent::setOptions($option);
        foreach ($option as $name => $value) {
            // @codingStandardsIgnoreStart
            switch ($name) {
                case 'binStubs':
                    $this->setBinStubs($value);
                    break;

                case 'clean':
                    $this->setClean($value);
                    break;

                case 'fullIndex':
                    $this->setFullIndex($value);
                    break;

                case 'jobs':
                    $this->setJobs($value);
                    break;

                case 'local':
                    $this->setLocal($value);
                    break;

                case 'deployment':
                    $this->setDeployment($value);
                    break;

                case 'force':
                    $this->setForce($value);
                    break;

                case 'frozen':
                    $this->setFrozen($value);
                    break;

                case 'system':
                    $this->setSystem($value);
                    break;

                case 'noCache':
                    $this->setNoCache($value);
                    break;

                case 'noPrune':
                    $this->setNoPrune($value);
                    break;

                case 'path':
                    $this->setPath($value);
                    break;

                case 'quiet':
                    $this->setQuiet($value);
                    break;

                case 'retry':
                    $this->setRetry($value);
                    break;

                case 'shebang':
                    $this->setShebang($value);
                    break;

                case 'standalone':
                    $this->setStandalone($value);
                    break;

                case 'trustPolicy':
                    $this->setTrustPolicy($value);
                    break;

                case 'withOrWithout':
                    $this->setWithOrWithout($value);
                    break;
            }
            // @codingStandardsIgnoreEnd
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandOptions(): array
    {
        return parent::getCommandOptions() + [
            'binstubs' => $this->getBinStubs(),
            'clean' => $this->getClean(),
            'full-index' => $this->getFullIndex(),
            'jobs' => $this->getJobs(),
            'local' => $this->getLocal(),
            'deployment' => $this->getDeployment(),
            'force' => $this->getForce(),
            'frozen' => $this->getFrozen(),
            'system' => $this->getSystem(),
            'no-cache' => $this->getNoCache(),
            'no-prune' => $this->getNoPrune(),
            'path' => $this->getPath(),
            'quiet' => $this->getQuiet(),
            'retry' => $this->getRetry(),
            'shebang' => $this->getShebang(),
            'standalone' => $this->getStandalone(),
            'trust-policy' => $this->getTrustPolicy(),
            'with|without' => $this->getWithOrWithout(),
        ];
    }
}
