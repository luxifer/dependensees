<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Repository\ArrayRepository;
use Composer\Package\CompletePackage;
use Composer\DependencyResolver\Pool;
use Composer\Repository\PlatformRepository;
use Composer\Repository\CompositeRepository;
use Guzzle\Http\Client;
use DependenSees\Sort\VersionSort;
use DependenSees\Trim\VersionTrim;
use Composer\Package\Version\VersionParser;

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
        $client = new Client($this->url);
        $sort = new VersionSort();
        $trim = new VersionTrim();
        $table = $this->getHelperSet()->get('table');
        $table->setHeaders(array(
            'Name',
            'Installed',
            'Available',
            'Up to date'
        ));
        $pass = 0;
        $count = 0;

        $output->writeLn(sprintf('Name        : <comment>%s</comment>', $package->getName()));
        $output->writeLn(sprintf('version     : <comment>%s</comment>', $package->getPrettyVersion()));
        $output->writeLn(sprintf('Description : <comment>%s</comment>', $package->getDescription()));
        $output->writeLn('');
        $output->writeLn('Processing...');
        $output->writeLn('');

        foreach ($requires as $name => $link) {
            $match = $local->findPackages($name);
            foreach ($match as $package) {
                if ($package instanceof CompletePackage) {
                    $count += 1;
                    $response = json_decode($client->get('/packages/'.$package->getName().'.json')->send()->getBody(), true);
                    $versions = $response['package']['versions'];
                    $stability = VersionParser::parseStability($package->getPrettyVersion());
                    $versions = $trim->trim($versions, $stability);
                    $versions = $sort->nameSort($versions);
                    //$versions = $sort->timeSort($versions);
                    $latest = array_shift($versions);
                    $pass += ($package->getPrettyVersion() === $latest['version']) ? 1 : 0;
                    $status = ($package->getPrettyVersion() === $latest['version']) ? 'OK' : 'KO';
                    $table->addRow(array(
                        $package->getName(),
                        $package->getPrettyVersion(),
                        $latest['version'],
                        $status
                    ));
                }
            }
        }

        $table->render($output);
        $output->writeLn('');
        $output->writeLn(sprintf('%d of %d packages are up to date.', $pass, $count));

        return !($pass == $count);
    }
}