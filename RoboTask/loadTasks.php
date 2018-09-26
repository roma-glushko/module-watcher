<?php

namespace ProjectUpdateWatcher\RoboTask;

/**
 * Trait loadTasks
 */
trait loadTasks
{
    /**
     * @param null|string $pathToComposer
     *
     * @return ComposerOutdated
     */
    protected function taskComposerOutdated($pathToComposer = null)
    {
        return $this->task(ComposerOutdated::class, $pathToComposer);
    }
}

