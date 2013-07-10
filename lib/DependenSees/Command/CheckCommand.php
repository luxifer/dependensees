<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Package\Version\VersionParser;
use Composer\Repository\ArrayRepository;
use Composer\Package\CompletePackage;
use Composer\DependencyResolver\Pool;
use Composer\Repository\PlatformRepository;
use Composer\Repository\CompositeRepository;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dependensees')
            ->setDescription('Command-line tool to check composer dependencies version')
            ->setHelp($this->logo)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composer = $this->getComposer(false);
        $manager = $composer->getRepositoryManager();
        $package = $composer->getPackage();
        $requires = $package->getRequires();
        $local = $manager->getLocalRepository();
        $repositories = $manager->getRepositories();
        $packagist = $repositories[0];
        $version = new VersionParser();
        $pool = new Pool($package->getMinimumStability());
        $platformRepo = new PlatformRepository;
        $installedRepo = new CompositeRepository(array($local, $platformRepo));
        $repos = new CompositeRepository(array_merge(array($installedRepo), $composer->getRepositoryManager()->getRepositories()));

        foreach ($requires as $name => $link) {
            $match = $local->findPackages($name);
            foreach ($match as $package) {
                if ($package instanceof CompletePackage) {
                    var_dump($repos->search($package->getName(), ArrayRepository::SEARCH_NAME));
                    $output->writeLn(sprintf('%s <comment>%s</comment>', str_pad($package->getName(), 25), $package->getPrettyVersion()));
                }
            }
        }
    }
}