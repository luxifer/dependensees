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
use Guzzle\Http\Client;

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
        $repositories = $manager->getRepositories();
        $packagist = $repositories[0];
        $package = $composer->getPackage();
        $requires = $package->getRequires();
        $local = $manager->getLocalRepository();
        $version = new VersionParser();
        $client = new Client($this->url);

        foreach ($requires as $name => $link) {
            $match = $local->findPackages($name);
            foreach ($match as $package) {
                if ($package instanceof CompletePackage) {
                    $response = $client->get('/packages/'.$package->getName().'.json')->send()->getBody();
                    var_dump(json_decode($response, true));
                    $output->writeLn(sprintf('%s <comment>%s</comment>', str_pad($package->getName(), 25), $version->formatVersion($package)));
                }
            }
        }
    }
}