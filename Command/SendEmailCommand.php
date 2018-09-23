<?php
/**
 * Created by PhpStorm.
 * User: glushko
 * Date: 9/23/18
 * Time: 12:23 AM
 */

namespace ProjectUpdateWatcher\Command;

use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;
use Symfony\Component\Console\Command\Command;
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

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $emailSubject = 'Project Update Report';
        $fromEmail = ['test@test.com'];
        $toEmails = ['test1@test.com', 'test2@test.com'];
        $emailBody = 'Here is the message itself';

        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($emailSubject))
            ->setFrom($fromEmail)
            ->setTo($toEmails)
            ->setBody($emailBody);

        return $mailer->send($message) > 0;
    }
}