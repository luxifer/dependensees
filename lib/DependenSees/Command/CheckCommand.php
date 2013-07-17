<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DependenSees\Helper\TableHelper;
use DependenSees\Parser\RequireParser;
use DependenSees\Builder\StatusBuilder;

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
        $local = $manager->getLocalRepository();
        $table = array('header' => array(), 'rows' => array());
        $table['header'] = array(
            'name'     => 'Name',
            'current'  => 'Installed',
            'required' => 'Required',
            'latest'   => 'Available',
            'status'   => 'Outdated'
        );
        $outdated = 0;
        $count = 0;
        $parser = new RequireParser($local);

        $output->writeLn(sprintf('Name        : <comment>%s</comment>', $package->getName()));
        $output->writeLn(sprintf('version     : <comment>%s</comment>', $package->getPrettyVersion()));
        $output->writeLn(sprintf('Description : <comment>%s</comment>', $package->getDescription()));
        $output->writeLn(sprintf('Stability   : <comment>%s</comment>', $package->getStability()));
        $output->writeLn('');
        $output->write('Processing');

        
        $requires = $parser->setRequires($package->getRequires())->check($output);
        $count += $parser->countPackages();
        $outdated += $parser->countOutdatedPackages();
        $devRequires = $parser->setRequires($package->getDevRequires())->check($output);
        $count += $parser->countPackages();
        $outdated += $parser->countOutdatedPackages();

        $output->writeLn('');
        $output->writeLn('');

        $table['rows'] = array_merge($requires, $devRequires);
        $tableHelper = new TableHelper($table);
        $tableHelper->render($output);

        $output->writeLn('');
        $output->writeLn(sprintf('<comment>%d</comment> of <comment>%d</comment> packages are outdated.', $outdated, $count));
        $output->writeLn('');
        $output->write('Building HTML status page... ');

        $path = getcwd();

        $builder = new StatusBuilder($path);
        $builder->render($package, $requires);
        $output->writeLn('Done!');

        return ($outdated == $count);
    }
}