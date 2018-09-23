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
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom(['r.glushko@atwix.com' => 'Roman Glushko'])
            ->setTo(['roman.glushko.m@gmail.com' => 'Roman Glushko'])
            ->setBody('Here is the message itself');

        return $mailer->send($message) > 0;
    }
}