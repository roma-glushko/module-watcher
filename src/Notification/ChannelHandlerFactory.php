<?php

declare(strict_types=1);

namespace ModuleWatcher\Notification;

use InvalidArgumentException;
use ModuleWatcher\Project\VariableProcessor;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\NullHandler;
use Monolog\Logger;

/**
 *
 */
class ChannelHandlerFactory
{
    /**
     * @var array
     */
    private $allowedTypes = [
        'native-mail',
        'slack'
    ];

    /**
     * @var VariableProcessor
     */
    private $variableProcessor;

    /**
     * @param VariableProcessor $variableProcessor
     */
    public function __construct()
    {
        $this->variableProcessor = new VariableProcessor();
    }

    /**
     * @param array $projectData
     * @param array $notificationChannelData
     * @param string $branchName
     *
     * @return HandlerInterface
     */
    public function create(array $projectData, array $notificationChannelData, string $branchName): HandlerInterface
    {
        $type = $notificationChannelData['type'] ?? null;

        if (null === $type) {
            throw new InvalidArgumentException('Notification channel is not specified');
        }

        if (!in_array($type, $this->allowedTypes, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Notification channel type is not allowed or misspelled. Allowed types: %s',
                    implode(', ', $this->allowedTypes)
                )
            );
        }

        // todo: move to separate factories to be able to share handlers with system notifications

        if ('native-mail' === $type) {
            return $this->createNativeMailHandler($projectData, $notificationChannelData, $branchName);
        }

        if ('slack' === $type) {
            return $this->createSlackHandler($projectData, $notificationChannelData, $branchName);
        }
    }

    /**
     * @param array $projectData
     * @param array $notificationChannelData
     * @param string $branchName
     *
     * @return NativeMailerHandler
     */
    private function createNativeMailHandler(array $projectData, array $notificationChannelData, string $branchName)
    {
        $recipients = $notificationChannelData['recipients'] ?? [];
        $fromEmail = $notificationChannelData['fromEmail'] ?? 'module.watcher@example.com';
        $subject = $notificationChannelData['subject'] ??
            '%project-name%: Module Watcher - Weekly Report [%project-branch%]';

        $subject = $this->variableProcessor->process($subject, $projectData, $branchName);

        $mailHandler =  new NativeMailerHandler(
            $recipients,
            $subject,
            $fromEmail,
            Logger::INFO
        );

        $mailHandler->setFormatter(new HtmlFormatter());

        return $mailHandler;
    }

    /**
     * @param array $projectData
     * @param array $notificationChannelData
     * @param string $branchName
     *
     * @return NullHandler
     */
    private function createSlackHandler(array $projectData, array $notificationChannelData, string $branchName)
    {
        // todo: implement Slack Handler
        return new NullHandler();
    }
}