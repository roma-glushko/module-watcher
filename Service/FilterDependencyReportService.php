<?php
/**
 * This file is part of project-update-watcher <https://github.com/roma-glushko/project-update-watcher>
 *
 * @author Roman Glushko <https://github.com/roma-glushko>
 */

namespace ProjectUpdateWatcher\Service;

/**
 * Class FilterDependencyReportService
 */
class FilterDependencyReportService
{
    /**
     * @param string $dependencyReport
     * @param string[] $blacklist
     *
     * @return string[]
     */
    public function execute(string $dependencyReport, array $blacklist): array
    {
        $filteredReport = [];
        $dependencyList = explode(PHP_EOL, $dependencyReport);

        foreach ($dependencyList as $dependencyItem) {
            if (!$this->isBlacklisted($dependencyItem, $blacklist)) {
                $filteredReport[] = $dependencyItem;
            }
        }

        return $filteredReport;
    }

    /**
     * @param string $dependencyItem
     * @param array $blacklist
     *
     * @return bool
     */
    protected function isBlacklisted(string $dependencyItem, array $blacklist): bool
    {
        foreach ($blacklist as $blacklistPattern) {
            if (false !== strstr($dependencyItem, $blacklistPattern)) {
                return true;
            }
        }

        return false;
    }
}
