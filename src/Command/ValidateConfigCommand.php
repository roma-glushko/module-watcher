<?php

declare(strict_types=1);

namespace ModuleWatcher\Command;

use ModuleWatcher\Module\LocalModuleProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class ValidateConfigCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config:validate');
        $this->setDescription('Validate configuration file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Command will be implemented in next releases');

        return 0;
    }
}