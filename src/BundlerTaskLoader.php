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
        return $this->task(Task\BundleCheckTask::class, $options);
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleExecTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleExec(array $options = []): CollectionBuilder
    {
        return $this->task(Task\BundleExecTask::class, $options);
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleInstallTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleInstall(array $options = []): CollectionBuilder
    {
        return $this->task(Task\BundleInstallTask::class, $options);
    }

    /**
     * @return \Sweetchuck\Robo\Bundler\Task\BundleShowPathsTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskBundleShowPaths(array $options = []): CollectionBuilder
    {
        return $this->task(Task\BundleShowPathsTask::class, $options);
    }
}
