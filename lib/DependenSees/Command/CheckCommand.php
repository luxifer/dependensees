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
use DependenSees\Helper\TableHelper;

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
        $table = array('header' => array(), 'rows' => array());
        $table['header'] = array(
            'Name',
            'Installed',
            'Available',
            'Outdated'
        );
        $outdated = 0;
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
                    $latest = array_shift($versions);
                    $outdated += ($package->getPrettyVersion() === $latest['version']) ? 0 : 1;
                    $status = ($package->getPrettyVersion() === $latest['version']) ? '-' : 'Yes';
                    $table['rows'][] = array(
                        $package->getName(),
                        $package->getPrettyVersion(),
                        $latest['version'],
                        $status
                    );
                }
            }
        }

        $tableHelper = new TableHelper($table);
        $tableHelper->render($output);

        $output->writeLn('');
        $output->writeLn(sprintf('%d of %d packages are outdated.', $outdated, $count));

        return ($outdated == $count);
    }
}