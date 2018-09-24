<?php
/**
 * This file is part of project-update-watcher <https://github.com/roma-glushko/project-update-watcher>
 *
 * @author Roman Glushko <https://github.com/roma-glushko>
 */

namespace ProjectUpdateWatcher\Command;

use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendEmailCommand
 */
class SendEmailCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->addArgument('dependencyList', InputArgument::REQUIRED | InputArgument::IS_ARRAY);
        $this->addOption('emailSubject', 's', InputArgument::REQUIRED);
        $this->addOption('fromEmail', 'f',InputArgument::REQUIRED);
        $this->addOption('toEmails', 't',InputArgument::REQUIRED | InputArgument::IS_ARRAY);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $toEmails = $input->getOption('toEmails');
        $fromEmail = $input->getOption('fromEmail');
        $emailSubject = $input->getOption('emailSubject');
        $dependencyList = $input->getArgument('dependencyList');

        $emailBody = implode(PHP_EOL, $dependencyList);

        $transport = $this->getEmailTransport();
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($emailSubject))
            ->setFrom($fromEmail)
            ->setTo($toEmails)
            ->setBody($emailBody);

        return $mailer->send($message) > 0;
    }

    /**
     * @return Swift_SendmailTransport
     */
    protected function getEmailTransport()
    {
        return new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
    }
}
