<?php

namespace Cheppers\Robo\Bundler\Task;

use Cheppers\AssetJar\AssetJarAware;
use Cheppers\AssetJar\AssetJarAwareInterface;
use Cheppers\Robo\Bundler\Utils;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Robo\Contract\OutputAwareInterface;
use Robo\Result;
use Robo\Task\BaseTask as RoboBaseTask;
use Robo\TaskInfo;
use Symfony\Component\Process\Process;

abstract class BaseTask extends RoboBaseTask implements AssetJarAwareInterface, CommandInterface, OutputAwareInterface
{
    use AssetJarAware;
    use OutputAwareTrait;

    /**
     * @var string
     */
    protected $processClass = Process::class;

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string
     */
    protected $taskName = '';

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var int
     */
    protected $actionExitCode = 0;

    /**
     * @var string
     */
    protected $actionStdOutput = '';

    /**
     * @var string
     */
    protected $actionStdError = '';

    /**
     * @var array
     */
    protected $assets = [];

    //region Options.

    //region Option - workingDirectory.
    /**
     * Directory to step in before run the `scss-lint`.
     *
     * @var string
     */
    protected $workingDirectory = '';

    public function getWorkingDirectory(): string
    {
        return $this->workingDirectory;
    }

    /**
     * Set the current working directory.
     *
     * @return $this
     */
    public function setWorkingDirectory(string $value)
    {
        $this->workingDirectory = $value;

        return $this;
    }
    //endregion

    //region Option - bundleGemFile.
    /**
     * @var string
     */
    protected $bundleGemFile = null;

    public function getBundleGemFile(): ?string
    {
        return $this->bundleGemFile;
    }

    /**
     * @return $this
     */
    public function setBundleGemFile(?string $bundleGemFile)
    {
        $this->bundleGemFile = $bundleGemFile;

        return $this;
    }
    //endregion

    //region Option - gemFile.
    /**
     * @var string
     */
    protected $gemFile = '';

    public function getGemFile(): string
    {
        return $this->gemFile;
    }

    /**
     * @return $this
     */
    public function setGemFile(string $gemFile)
    {
        $this->gemFile = $gemFile;

        return $this;
    }
    //endregion

    //region Option - bundleExecutable.
    /**
     * @var string
     */
    protected $bundleExecutable = 'bundle';

    protected function getBundleExecutable(): string
    {
        return $this->bundleExecutable;
    }

    /**
     * @return $this
     */
    protected function setBundleExecutable(string $value)
    {
        $this->bundleExecutable = $value;

        return $this;
    }
    //endregion

    //region Option - verbose.
    /**
     * @var null|bool
     */
    protected $verbose = null;

    public function getVerbose(): ?bool
    {
        return $this->verbose;
    }

    /**
     * @return $this
     */
    public function setVerbose(?bool $value)
    {
        $this->verbose = $value;

        return $this;
    }
    //endregion

    //region Option - noColor.
    /**
     * @var null|bool
     */
    protected $noColor = null;

    public function getNoColor(): ?bool
    {
        return $this->noColor;
    }

    /**
     * @return $this
     */
    public function setNoColor(?bool $value)
    {
        $this->noColor = $value;

        return $this;
    }
    //endregion

    //endregion

    protected $options = [
        'BUNDLE_GEMFILE' => 'environment',
        'gemfile' => 'value',
        'verbose' => 'tri-state',
        'no-color' => 'tri-state',
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        $this
            ->initOptions()
            ->setOptions($options);
    }

    /**
     * @return $this
     */
    public function setOptions(array $option)
    {
        foreach ($option as $name => $value) {
            // @codingStandardsIgnoreStart
            switch ($name) {
                case 'workingDirectory':
                    $this->setWorkingDirectory($value);
                    break;

                case 'bundleGemFile':
                    $this->setBundleGemFile($value);
                    break;

                case 'gemFile':
                    $this->setGemFile($value);
                    break;

                case 'bundleExecutable':
                    $this->setBundleExecutable($value);
                    break;

                case 'verbose':
                    $this->setVerbose($value);
                    break;

                case 'noColor':
                    $this->setNoColor($value);
                    break;
            }
            // @codingStandardsIgnoreEnd
        }

        return $this;
    }

    protected function initOptions()
    {
        return $this;
    }

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        $options = $this->getCommandOptions();

        $envPattern = '';
        $envArgs = [];

        $cmdPattern = '';
        $cmdArgs = [];

        if ($this->getBundleExecutable()) {
            $cmdPattern .= '%s ';
            $cmdArgs[] = escapeshellcmd($this->getBundleExecutable());
        }

        $cmdPattern .= escapeshellcmd($this->action);

        foreach ($options as $optionName => $optionValue) {
            switch ($this->options[$optionName]) {
                case 'environment':
                    if ($optionValue !== null) {
                        $envPattern .= " {$optionName}=%s";
                        $envArgs[] = escapeshellarg($optionValue);
                    }
                    break;

                case 'value':
                    if ($optionValue) {
                        $cmdPattern .= " --$optionName=%s";
                        $cmdArgs[] = escapeshellarg($optionValue);
                    }
                    break;

                case 'value-optional':
                    if ($optionValue !== null) {
                        $cmdPattern .= " --$optionName";
                        $optionValue = (string) $optionValue;
                        if ($optionValue !== '') {
                            $cmdPattern .= "=%s";
                            $cmdArgs[] = escapeshellarg($optionValue);
                        }
                    }
                    break;

                case 'flag':
                    if ($optionValue) {
                        $cmdPattern .= " --$optionName";
                    }
                    break;

                case 'tri-state':
                    if ($optionValue !== null) {
                        $cmdPattern .= $optionValue ? " --$optionName" : " --no-$optionName";
                    }
                    break;

                case 'true|false':
                    $nameFilter = array_combine(
                        explode('|', $optionName),
                        [true, false]
                    );

                    foreach ($nameFilter as $name => $filter) {
                        $items = array_keys($optionValue, $filter, true);
                        if ($items) {
                            $cmdPattern .= " --$name=%s";
                            $cmdArgs[] = escapeshellarg(implode(' ', $items));
                        }
                    }
                    break;

                case 'space-separated':
                    $items = Utils::filterEnabled($optionValue);
                    if ($items) {
                        $cmdPattern .= " --$optionName=%s";
                        $cmdArgs[] = escapeshellarg(implode(' ', $items));
                    }
                    break;
            }
        }

        $env = vsprintf($envPattern, $envArgs);
        $command = '';
        if ($this->getWorkingDirectory()) {
            $command = sprintf('cd %s && ', escapeshellarg($this->getWorkingDirectory()));
        }

        $command .= $env ? ltrim("$env ") : '';

        return $command . vsprintf($cmdPattern, $cmdArgs);
    }

    public function run()
    {
        $this->command = $this->getCommand();

        return $this
            ->runHeader()
            ->runAction()
            ->runProcessOutputs()
            ->runReleaseAssets()
            ->runReturn();
    }

    /**
     * @return $this
     */
    protected function runHeader()
    {
        $this->printTaskInfo($this->command);

        return $this;
    }

    /**
     * @return $this
     */
    protected function runAction()
    {
        /** @var Process $process */
        $process = new $this->processClass($this->command);

        $this->actionExitCode = $process->run(function ($type, $data) {
            $this->runCallback($type, $data);
        });
        $this->actionStdOutput = $process->getOutput();
        $this->actionStdError = $process->getErrorOutput();

        return $this;
    }

    /**
     * @return $this
     */
    protected function runProcessOutputs()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function runReleaseAssets()
    {
        if ($this->hasAssetJar()) {
            $assetJar = $this->getAssetJar();
            foreach ($this->assets as $name => $value) {
                $mapping = $this->getAssetJarMap($name);
                if ($mapping) {
                    $assetJar->setValue($mapping, $value);
                }
            }
        }

        return $this;
    }

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            $this->actionExitCode,
            $this->actionStdError,
            $this->assets
        );
    }

    protected function runCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                $this->output()->write($data);
                break;

            case Process::ERR:
                $this->printTaskError($data);
                break;
        }
    }

    protected function getCommandOptions(): array
    {
        return [
            'BUNDLE_GEMFILE' => $this->getBundleGemFile(),
            'gemfile' => $this->getGemFile(),
            'verbose' => $this->getVerbose(),
            'no-color' => $this->getNoColor(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        return parent::getTaskContext($context);
    }
}
