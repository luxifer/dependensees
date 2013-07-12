<?php

namespace DependenSees\Parser;

use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\CompletePackage;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class RequireParser
{
    protected $requires;
    protected $local;
    protected $outdated;
    protected $count;
    protected $parser;

    public function __construct(InstalledRepositoryInterface $local)
    {
        $this->local = $local;
        $this->parser = new VersionsParser;

        return $this;
    }

    public function setRequires(array $requires)
    {
        $this->requires = $requires;

        return $this;
    }

    public function check($output)
    {
        $this->outdated = 0;
        $this->count = 0;
        $rows = array();

        foreach ($this->requires as $name => $link) {
            $match = $this->local->findPackages($name);
            foreach ($match as $package) {
                if ($package instanceof CompletePackage) {
                    $this->count += 1;
                    $latest = $this->parser->latest($package);
                    $this->outdated += ($package->getPrettyVersion() === $latest['version']) ? 0 : 1;
                    $status = ($package->getPrettyVersion() === $latest['version']) ? '-' : 'Yes';
                    $rows[] = array(
                        $package->getName(),
                        $package->getPrettyVersion(),
                        $latest['version'],
                        $status
                    );
                    $output->write('.');
                }
            }
        }

        return $rows;
    }

    public function countPackages()
    {
        return $this->count;
    }

    public function countOutdatedPackages()
    {
        return $this->outdated;
    }
}