<?php
/**
 * This file is part of project-update-watcher <https://github.com/roma-glushko/project-update-watcher>
 *
 * @author Roman Glushko <https://github.com/roma-glushko>
 */

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

