<?php

namespace Sweetchuck\Robo\Bundler;

use Robo\Collection\CollectionBuilder;

trait BundlerTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleCheckTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleCheck(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Bundler\Task\BundleCheckTask $task */
        $task = $this->task(Task\BundleCheckTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleExecTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleExec(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Bundler\Task\BundleExecTask $task */
        $task = $this->task(Task\BundleExecTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleInstallTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleInstall(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Bundler\Task\BundleInstallTask $task */
        $task = $this->task(Task\BundleInstallTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundlePlatformRubyVersionTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundlePlatformRubyVersion(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Bundler\Task\BundlePlatformRubyVersionTask $task */
        $task = $this->task(Task\BundlePlatformRubyVersionTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleShowPathsTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleShowPaths(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\Bundler\Task\BundleShowPathsTask $task */
        $task = $this->task(Task\BundleShowPathsTask::class);
        $task->setOptions($options);

        return $task;
    }
}
