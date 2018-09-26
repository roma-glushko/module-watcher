<?php

namespace ProjectUpdateWatcher\RoboTask;

use Robo\Task\Composer\Base;

/**
 * Class ComposerOutdated
 */
class ComposerOutdated extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'outdated';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $command = $this->getCommand();

        $this->printTaskInfo('Checking Outdated Packages: {command}', ['command' => $command]);

        return $this->executeCommand($command);
    }
}
