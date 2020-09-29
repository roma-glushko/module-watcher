<?php

declare(strict_types=1);

namespace ModuleWatcher\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * ConfigManager loads and retrieves values from configuration files
 */
class ConfigManager
{
    /**
     * @var array
     */
    private $configData;

    /**
     * @var string
     */
    private $configFile;

    /**
     * @var array
     */
    protected $parentNode;

    /**
     * @var string
     */
    protected $nodeIndex;

    /**
     * @param string $configFile
     */
    public function __construct(string $configFile)
    {
        $this->configFile = $configFile;
        $this->configData = Yaml::parseFile($configFile);
    }

    /**
     * Retrieve config node
     *
     * @param string $path
     * @param null $defaultValue
     * @param string $delimiter
     * @return string|array|null
     */
    public function get($path, $defaultValue = null, $delimiter = '/')
    {
        return $this->find($path, $delimiter) ? $this->parentNode[$this->nodeIndex] : $defaultValue;
    }

    /**
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * Retrieve configured projects
     *
     * @return array
     */
    public function getProjects(): array
    {
        return $this->get('projects', []);
    }

    /**
     * Retrieve project config by code
     *
     * @param string $projectCode
     * @return array
     */
    public function getProject(string $projectCode): array
    {
        return $this->get('projects/' . $projectCode, []);
    }

    /**
     * Retrieve all notification channels
     *
     * @return array
     */
    public function getNotificationChannels(): array
    {
        return $this->get('notification-channels', []);
    }

    /**
     * Retrieve notification channel by code
     *
     * @param string $channelCode
     *
     * @return array
     */
    public function getNotificationChannel(string $channelCode): array
    {
        return $this->get('notification-channels/' . $channelCode, []);
    }

    /**
     * Retrieve proxy packagists
     *
     * @return []
     */
    public function getProxyPackagists(): array
    {
        return $this->get('proxy-packagists', []);
    }

    /**
     * Retrieve tmp directory
     *
     * @return string
     */
    public function getTmpDir(): string
    {
        return $this->get('tmp-dir', './tmp');
    }

    /**
     * Retrieve Git binary path
     *
     * @return string|null
     */
    public function getGitBin(): ?string
    {
        return $this->get('git-bin', null);
    }

    /**
     * Finds node in nested array and saves its index and parent node reference
     *
     * @param array|string $path
     * @param string $delimiter
     * @param bool $populate
     *
     * @return bool
     */
    private function find($path, $delimiter, $populate = false)
    {
        if (is_array($path)) {
            $path = implode($delimiter, $path);
        }

        if ($path === null) {
            return false;
        }

        $currentNode = &$this->configData;
        $path = explode($delimiter, $path);

        foreach ($path as $index) {
            if (!is_array($currentNode)) {
                return false;
            }

            if (!array_key_exists($index, $currentNode)) {
                if (!$populate) {
                    return false;
                }

                $currentNode[$index] = [];
            }

            $this->nodeIndex = $index;
            $this->parentNode = &$currentNode;
            $currentNode = &$currentNode[$index];
        }

        return true;
    }

}