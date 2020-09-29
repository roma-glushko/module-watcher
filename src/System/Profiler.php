<?php

declare(strict_types=1);

namespace ModuleWatcher\System;

/**
 *
 */
class Profiler
{
    private static $measurments = [];

    /**
     * @param string $measurementName
     */
    public static function start(string $measurementName)
    {
        self::$measurments[$measurementName] = [
            'exec_time_start' => microtime(true),
            'memory_usage_start' => memory_get_usage(),
        ];
    }

    /**
     * @param string $measurementName
     */
    public static function end(string $measurementName)
    {
        self::$measurments[$measurementName]['exec_time_end'] = microtime(true);
        self::$measurments[$measurementName]['memory_usage_end'] = memory_get_usage();
    }

    /**
     * @param string $measurementName
     */
    public static function get(string $measurementName): array
    {
        if (!array_key_exists($measurementName, static::$measurments)) {
            return [];
        }

        // todo: check if measurment is end
        $measurementValue = static::$measurments[$measurementName];

        return static::getSummary($measurementValue);
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $result = [];

        foreach (static::$measurments as $measurementName => $measurementValue) {
            $result[$measurementName] = static::getSummary($measurementValue);
        }

        return $result;
    }

    private static function getSummary(array $measurementValue): array
    {
        $executionTime = $measurementValue['exec_time_end'] - $measurementValue['exec_time_start'];
        $memoryUsage = $measurementValue['memory_usage_end'] - $measurementValue['memory_usage_start'];

        return [
            'exec_time' => date('H:i:s', (int) $executionTime),
            'memory_usage' => round(($memoryUsage / 1024) / 1024) .' MB',
        ];
    }
}