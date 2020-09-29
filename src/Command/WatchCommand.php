<?php

declare(strict_types=1);

namespace ModuleWatcher\Command;

use ModuleWatcher\Module\ComposerModuleProvider;
use ModuleWatcher\Module\LocalModuleProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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

        $this->setName('project:watch');
        $this->setDescription('Watch module updates for the configured projects');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Profiler::start('WATCH-COMMAND');

        // (new ComposerModuleProvider())->get('./tmp/src/composer.json', './tmp/vendor','./tmp/vendor/home');

        (new LocalModuleProvider())->get('./tmp/src');
        // Profiler::end('WATCH-COMMAND');

        // var_dump(Profiler::getAll());

        return 0;
    }
}