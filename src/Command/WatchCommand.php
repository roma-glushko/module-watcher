<?php

declare(strict_types=1);

namespace ModuleVersionWatcher\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class WatchCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('watch');
        $this->setDescription('');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        return 0;
    }
}