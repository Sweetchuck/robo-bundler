<?php

namespace Sweetchuck\Robo\Bundler\Task;

use Sweetchuck\Robo\Bundler\Utils;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Robo\Contract\OutputAwareInterface;
use Robo\Result;
use Robo\Task\BaseTask as RoboBaseTask;
use Robo\TaskInfo;
use Symfony\Component\Process\Process;

abstract class BaseTask extends RoboBaseTask implements CommandInterface, OutputAwareInterface
{
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

    // region Option - assetNamePrefix.
    /**
     * @var string
     */
    protected $assetNamePrefix = '';

    public function getAssetNamePrefix(): string
    {
        return $this->assetNamePrefix;
    }

    /**
     * @return $this
     */
    public function setAssetNamePrefix(string $value)
    {
        $this->assetNamePrefix = $value;

        return $this;
    }
    // endregion

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

    // region rubyExecutable
    /**
     * @var string
     */
    protected $rubyExecutable = '';

    public function getRubyExecutable(): string
    {
        return $this->rubyExecutable;
    }

    /**
     * @return $this
     */
    public function setRubyExecutable(string $value)
    {
        $this->rubyExecutable = $value;

        return $this;
    }
    // endregion

    //region Option - bundleExecutable.
    /**
     * @var string
     */
    protected $bundleExecutable = 'bundle';

    public function getBundleExecutable(): string
    {
        return $this->bundleExecutable;
    }

    /**
     * @return $this
     */
    public function setBundleExecutable(string $value)
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

    /**
     * @return $this
     */
    public function setOptions(array $option)
    {
        foreach ($option as $name => $value) {
            switch ($name) {
                case 'assetNamePrefix':
                    $this->setAssetNamePrefix($value);
                    break;

                case 'workingDirectory':
                    $this->setWorkingDirectory($value);
                    break;

                case 'bundleGemFile':
                    $this->setBundleGemFile($value);
                    break;

                case 'gemFile':
                    $this->setGemFile($value);
                    break;

                case 'rubyExecutable':
                    $this->setRubyExecutable($value);
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
        }

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
        $envPattern = [];
        $envArgs = [];

        $cmdPattern = [];
        $cmdArgs = [];

        $cmdAsIs = [];

        if ($this->getRubyExecutable()) {
            $cmdPattern[] = '%s';
            $cmdArgs[] = escapeshellcmd($this->getRubyExecutable());
        }

        $cmdPattern[] = '%s';
        $cmdArgs[] = escapeshellcmd($this->getBundleExecutable());

        $cmdPattern[] = escapeshellcmd($this->action);

        foreach ($this->getCommandOptions() as $optionName => $option) {
            switch ($option['type']) {
                case 'environment':
                    if ($option['value'] !== null) {
                        $envPattern[] = "{$optionName}=%s";
                        $envArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'value':
                    if ($option['value']) {
                        $cmdPattern[] = "--$optionName=%s";
                        $cmdArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'value-optional':
                    if ($option['value'] !== null) {
                        $value = (string) $option['value'];
                        if ($value === '') {
                            $cmdPattern[] = "--{$optionName}";
                        } else {
                            $cmdPattern[] = "--{$optionName}=%s";
                            $cmdArgs[] = escapeshellarg($value);
                        }
                    }
                    break;

                case 'flag':
                    if ($option['value']) {
                        $cmdPattern[] = "--$optionName";
                    }
                    break;

                case 'tri-state':
                    if ($option['value'] !== null) {
                        $cmdPattern[] = $option['value'] ? "--$optionName" : "--no-$optionName";
                    }
                    break;

                case 'true|false':
                    $nameFilter = array_combine(
                        explode('|', $optionName),
                        [true, false]
                    );

                    foreach ($nameFilter as $name => $filter) {
                        $items = array_keys($option['value'], $filter, true);
                        if ($items) {
                            $cmdPattern[] = "--$name=%s";
                            $cmdArgs[] = escapeshellarg(implode(' ', $items));
                        }
                    }
                    break;

                case 'space-separated':
                    $items = Utils::filterEnabled($option['value']);
                    if ($items) {
                        $cmdPattern[] = "--$optionName=%s";
                        $cmdArgs[] = escapeshellarg(implode(' ', $items));
                    }
                    break;

                case 'as-is':
                    if ($option['value'] instanceof CommandInterface) {
                        $cmd = $option['value']->getCommand();
                    } else {
                        $cmd = (string) $option['value'];
                    }

                    if ($cmd) {
                        $cmdAsIs[] = $cmd;
                    }
                    break;
            }
        }

        $wd = $this->getWorkingDirectory();

        $chDir = $wd ? sprintf('cd %s &&', escapeshellarg($wd)) : '';
        $env = vsprintf(implode(' ', $envPattern), $envArgs);
        $cmd = vsprintf(implode(' ', $cmdPattern), $cmdArgs);
        $asIs = implode(' ', $cmdAsIs);

        return implode(' ', array_filter([$chDir, $env, $cmd, $asIs]));
    }

    public function run()
    {
        $this->command = $this->getCommand();

        return $this
            ->runHeader()
            ->runAction()
            ->runProcessOutputs()
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
        /** @var \Symfony\Component\Process\Process $process */
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

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            $this->actionExitCode,
            $this->actionStdError,
            $this->getAssetsWithPrefixedNames()
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
            'BUNDLE_GEMFILE' => [
                'type' => 'environment',
                'value' => $this->getBundleGemFile(),
            ],
            'gemfile' => [
                'type' => 'value',
                'value' => $this->getGemFile(),
            ],
            'verbose' => [
                'type' => 'tri-state',
                'value' => $this->getVerbose(),
            ],
            'no-color' => [
                'type' => 'tri-state',
                'value' => $this->getNoColor(),
            ],
        ];
    }

    protected function getAssetsWithPrefixedNames(): array
    {
        $prefix = $this->getAssetNamePrefix();
        if (!$prefix) {
            return $this->assets;
        }

        $data = [];
        foreach ($this->assets as $key => $value) {
            $data["{$prefix}{$key}"] = $value;
        }

        return $data;
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
