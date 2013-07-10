<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Package\Version\VersionParser;
use Composer\Repository\ArrayRepository;

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $this->getComposer(false)->getPackage();
        $requires = $package->getRequires();

        foreach ($requires as $name => $link) {
            $output->writeLn(sprintf('%s <comment>%s</comment>', str_pad($name, 30), $link->getPrettyConstraint()));
        }
    }
}