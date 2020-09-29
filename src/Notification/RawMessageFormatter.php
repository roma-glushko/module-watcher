<?php

declare(strict_types=1);

namespace ModuleWatcher\Notification;

use Monolog\Formatter\FormatterInterface;

/**
 *
 */
class RawMessageFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(array $record)
    {
        return $record['message'];
    }

    /**
     * @inheritDoc
     */
    public function formatBatch(array $records)
    {
        $message = '';

        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}