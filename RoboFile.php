<?php
/**
 * This file is part of project-update-watcher <https://github.com/roma-glushko/project-update-watcher>
 *
 * @author Roman Glushko <https://github.com/roma-glushko>
 */

require_once 'vendor/autoload.php';

use ProjectUpdateWatcher\Command\SendEmailCommand;
use ProjectUpdateWatcher\Service\FilterDependencyReportService;
use Robo\Tasks;

/**
 * Class RoboFile
 */
class RoboFile extends Tasks
{
    use NuvoleWeb\Robo\Task\Config\loadTasks;

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

        $emailSubject = $this->config('email.subject');
        $fromEmail = $this->config('email.fromEmail');
        $toEmails = $this->config('email.toEmails');
        $dependencyBlacklist = $this->config('blacklist');

        $this->taskGitStack()
            ->dir($projectPath)
            ->pull($originName, $branchName)
            ->run();

        $this->taskComposerInstall()
            ->dir($projectPath)
            ->run();

        $this->say('Looking for outdated dependencies..');

        $dependencyReport = $this->taskExec('composer')
            ->dir($projectPath)
            ->arg('outdated')
            ->silent(true)
            ->run();

        $dependencyList = (new FilterDependencyReportService())->execute(
            $dependencyReport->getMessage(),
            $dependencyBlacklist
        );

        $this->taskSymfonyCommand(new SendEmailCommand())
            ->arg('dependencyList', $dependencyList)
            ->opt('emailSubject', $emailSubject)
            ->opt('fromEmail', $fromEmail)
            ->opt('toEmails', $toEmails)
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
