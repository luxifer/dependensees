<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Dflydev\EmbeddedComposer\Core\EmbeddedComposerInterface;
use Composer\IO\ConsoleIO;
use Composer\Package\Version\VersionParser;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class CheckCommand extends Command
{
    protected $embeddedComposer;

    public function __construct(EmbeddedComposerInterface $embeddedComposer)
    {
        $this->embeddedComposer = $embeddedComposer;
        parent::__construct('dependensees');
    }

    protected function configure()
    {
        $this
            ->setName('dependensees')
            ->setDescription('Command-line tool to check composer dependencies version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new ConsoleIO($input, $output, $this->getHelperSet());
        $composer = $this->embeddedComposer->createComposer($io);
        $rootPackage = $composer->getPackage();
        $config = $composer->getConfig();
        $requires = $rootPackage->getRequires();
        $stability = $rootPackage->getMinimumStability();
        $parser = new VersionParser();

        foreach ($requires as $name => $package) {
            $match = $this->embeddedComposer->findPackage($name);
            $string = sprintf('%s || %s', $parser->formatVersion($match), $package->getConstraint()->getPrettyString());
            $output->writeLn($string);
        }
    }
}