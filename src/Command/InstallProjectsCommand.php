<?php

declare(strict_types=1);

namespace ModuleWatcher\Command;

use ModuleWatcher\Project\ProjectFetcher;
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
        (new ProjectFetcher())->fetch('git@github.com:X/Y.git', 'tmp/');

        return 0;
    }
}