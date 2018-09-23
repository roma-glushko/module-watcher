<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

require_once 'vendor/autoload.php';

use ProjectUpdateWatcher\Command\SendEmailCommand;
use Robo\Tasks;

/**
 * Class RoboFile
 */
class RoboFile extends Tasks
{
    use NuvoleWeb\Robo\Task\Config\loadTasks;

    const LOG_PATH = './log';

    const RESOURCES_PATH = './resources';

    /**
     * @return void
     */
    public function watcherInstall()
    {
        $projectPath = $this->getProjectPath();
        $logPath = $this->getLogPath();
        $resourcesPath = $this->getResourcesPath();
        $branchName = $this->config('project.branchName');
        $projectRepositoryUrl = $this->config('project.repositoryUrl');

        $this->taskFilesystemStack()
            ->mkdir($projectPath)
            ->mkdir($logPath)
            ->mkdir($resourcesPath)
            ->run();

        $this->taskGitStack()
            ->cloneShallow($projectRepositoryUrl, $projectPath, $branchName)
            ->run();

        $this->taskComposerInstall()
            ->dir($projectPath)
            ->run();
    }

    /**
     * Check outdated dependencies in the project
     *
     * @return void
     */
    public function watcherCheckOutdatedDependency()
    {
        $projectPath = $this->getProjectPath();
        $branchName = $this->config('project.branchName');
        $originName = $this->config('project.originName');

        $this->taskGitStack()
            ->dir($projectPath)
            ->pull($originName, $branchName)
            ->run();

        $this->taskComposerInstall()
            ->dir($projectPath)
            ->run();

        $this->say('Looking for outdated dependencies..');

        $outdatedDependencyReport = $this->taskExec('composer')
            // ->dir($projectPath)
            ->arg('outdated')
            ->silent(true)
            ->run();

        $this->taskSymfonyCommand(new SendEmailCommand())
            ->run();
    }

    /**
     * @return string
     */
    protected function getProjectPath()
    {
        return $this->config('watcher.projectPath');
    }

    /**
     * @return string
     */
    protected function getLogPath()
    {
        return $this->config('watcher.logPath');
    }

    /**
     * @return string
     */
    protected function getResourcesPath()
    {
        return $this->config('watcher.resourcesPath');
    }
}