<?php

declare(strict_types=1);

namespace ModuleWatcher\Command;

use ModuleWatcher\Config\ConfigManager;
use ModuleWatcher\Project\NotificationManager;
use ModuleWatcher\Project\ProjectFetcher;
use ModuleWatcher\Template\TemplateRenderer;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class InstallProjectsCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('project:install');
        $this->setDescription('Check all configured projects and install them if they are not ready');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //(new ProjectFetcher())->fetch('git@github.com:Atwix/Cabinets-m2.git', 'tmp/cab');
        //var_dump((new ConfigManager('module-watcher.yaml'))->getProjects());

        $configManager = new ConfigManager('module-watcher.yaml');
        $notificationLogger = (new NotificationManager($configManager))->getNotificationLogger(
            'cabinetsdotcom',
            'master'
        );

        $reportTemplate = (new TemplateRenderer())->render(
            'module-watcher-report.html.twig',
            ['project' => $configManager->getProject('cabinetsdotcom')]
        );

        $notificationLogger->log(Logger::INFO, $reportTemplate);


        return 0;
    }
}