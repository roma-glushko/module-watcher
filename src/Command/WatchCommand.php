<?php

declare(strict_types=1);

namespace ModuleWatcher\Command;

use Composer\Composer;
use Composer\Factory as ComposerFactory;
use Composer\IO\BufferIO;
use Composer\Package\AliasPackage;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Semver\Comparator;
use Composer\Semver\Constraint\Constraint;
use ModuleWatcher\System\Profiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class WatchCommand extends Command
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('watch');
        $this->setDescription('Review module updates for the current list of projects');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Profiler::start('WATCH-COMMAND');
        $composer = $this->getComposer('./tmp/composer.json', './tmp/vendor','./tmp/vendor/home');

        $rootPackage = $composer->getPackage();
        $repositoryManager = $composer->getRepositoryManager();
        $lockedPackages = $composer->getLocker()->getLockedRepository();

        /** @var Link[] $rootRequires */
        $rootRequires = array_merge_recursive(
            $rootPackage->getRequires(),
            $rootPackage->getDevRequires() // modules can be required as dev dependency
        );

        $preferStable = (true === $rootPackage->getPreferStable()) ||
            ('stable' === $rootPackage->getMinimumStability());

        foreach ($rootRequires as $requirePackage) {
            $package = $lockedPackages->findPackage(
                $requirePackage->getTarget(),
                $requirePackage->getPrettyConstraint()
            );

            if (null === $package) {
                // throw an error
                continue;
            }

            if ($package instanceof AliasPackage) {
                continue;
            }

            // todo: move to configs
            if ('magento2-module' !== $package->getType() && 'magento2-component' !== $package->getType()) {
                continue;
            }

            Profiler::start('WATCH-COMMAND::FIND-LAST-VERSION::' . $package->getName());
            $higherPackages = $repositoryManager->findPackages(
                $package->getName(), new Constraint('>', $package->getVersion())
            );
            Profiler::end('WATCH-COMMAND::FIND-LAST-VERSION::' . $package->getName());

            if ($preferStable) {
                $higherPackages = array_filter($higherPackages, function (PackageInterface $package) {
                    return 'stable' === $package->getStability();
                });
            }

            if (count($higherPackages) === 0) {
                continue;
            }

            // Sort packages by highest version to lowest
            usort($higherPackages, function (PackageInterface $p1, PackageInterface $p2) {
                return Comparator::compare($p1->getVersion(), '<', $p2->getVersion());
            });

            // Push actual and last package on outdated array
            var_dump([
                'package' => $package->getPrettyName(),
                'current_version' => $package->getPrettyVersion(),
                'newest_version' => $higherPackages[0]->getPrettyVersion(),
            ]);
        }
        Profiler::end('WATCH-COMMAND');

        var_dump(Profiler::getAll());

        return 0;
    }

    /**
     * @return Composer
     */
    private function getComposer(string $composerJsonPath, string $vendorDirPath, string $composerHomePath): Composer
    {
        putenv('COMPOSER_HOME=' . $composerHomePath);
        putenv('COMPOSER_VENDOR_DIR=' . $vendorDirPath);

        return ComposerFactory::create(
            new BufferIO(),
            $composerJsonPath
        );
    }

    /**
     * Checks if the passed packaged is system package
     *
     * @param string $packageName
     *
     * @return bool
     */
    private function isMagentoPackage(string $packageName)
    {
        return 1 === preg_match('/^magento\/*/', $packageName);
    }
}