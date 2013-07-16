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
            'Name',
            'Installed',
            'Available',
            'Outdated'
        );
        $outdated = 0;
        $count = 0;
        $parser = new RequireParser($local);

        $output->writeLn(sprintf('Name        : <comment>%s</comment>', $package->getName()));
        $output->writeLn(sprintf('version     : <comment>%s</comment>', $package->getPrettyVersion()));
        $output->writeLn(sprintf('Description : <comment>%s</comment>', $package->getDescription()));
        $output->writeLn('');
        $output->write('Processing');

        
        $requires = $parser->setRequires($package->getRequires())->check($output);
        $count += $parser->countPackages();
        $outdated += $parser->countOutdatedPackages();
        $requires += $parser->setRequires($package->getDevRequires())->check($output);
        $count += $parser->countPackages();
        $outdated += $parser->countOutdatedPackages();

        $output->writeLn('');
        $output->writeLn('');

        $table['rows'] = $requires;
        $tableHelper = new TableHelper($table);
        $tableHelper->render($output);

        $output->writeLn('');
        $output->writeLn(sprintf('%d of %d packages are outdated.', $outdated, $count));

        $builder = new StatusBuilder();
        $builder->render($package, $requires);

        return ($outdated == $count);
    }
}