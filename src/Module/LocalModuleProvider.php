<?php

declare(strict_types=1);

namespace ModuleWatcher\Module;

use Composer\Package\Loader\JsonLoader;
use RuntimeException;
use Composer\Package\Loader\ArrayLoader;

/**
 *
 */
class LocalModuleProvider
{
    /**
     * Collects all local modules and provide list of third-parties that were installed via codebase (poor practice)
     *
     * @param string $projectRoot
     *
     * @return array
     */
    public function get(
        string $projectRoot
    ): array {
        $localModulePattern = $projectRoot . '/app/code/*/*/registration.php';

        $registrationFiles = glob($localModulePattern, GLOB_NOSORT);

        if ($registrationFiles === false) {
            throw new RuntimeException("glob() doesn't retrieve local modules");
        }

        $pathPartToRemove = explode('*/*', $localModulePattern);

        $localModules = [];
        $arrayLoader = new ArrayLoader();

        foreach ($registrationFiles as $registrationFile) {
            $autoloaderPrefix = str_replace($pathPartToRemove, '', $registrationFile);
            $vendorName = explode('/', trim($autoloaderPrefix, '\\'))[0]; // todo: check if the array is not empty

            // todo: filter all custom vendor modules

            $composerFilePath = dirname($registrationFile) . '/composer.json';

            if (!file_exists($composerFilePath) || !is_readable($composerFilePath)) {
                // todo: track this module anyway as it's not from the custom vendors
                continue;
            }

            try {
                $package = (new JsonLoader($arrayLoader))->load($composerFilePath);

                $localModules[] = [
                    'name' => $package->getName(),
                    'version' => $package->getPrettyVersion(),
                ];
            } catch (\Exception $e) {
                // todo: log the exception

                $localModules[] = [
                    'name' => str_replace('/', '_', trim($autoloaderPrefix, '\\')),
                    'version' => null,
                ];
            }
        }

        var_dump($localModules);

        return $localModules;
    }
}