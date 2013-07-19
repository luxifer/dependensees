<?php

namespace DependenSees\Parser;

use Composer\Repository\InstalledRepositoryInterface;
use Composer\Package\CompletePackage;
use DependenSees\Compare\SemanticCompare;

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
    protected $compare;

    public function __construct(InstalledRepositoryInterface $local)
    {
        $this->local = $local;
        $this->parser = new VersionsParser;
        $this->compare = new SemanticCompare;

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
                    $status = $this->compare->compare($package->getPrettyVersion(), $latest['version']);
                    $this->outdated += $status === SemanticCompare::SUCCESS ? 0 : 1;
                    $message = $this->getMessageForStatus($status);
                    $rows[] = array(
                        'name'     => $package->getName(),
                        'current'  => $package->getPrettyVersion(),
                        'required' => $link->getConstraint()->getPrettyString(),
                        'latest'   => $latest['version'],
                        'status'   => $message
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

    protected function getMessageForStatus($status)
    {
        switch ($status) {
            case SemanticCompare::SUCCESS:
                $message = '-';
                break;

            case SemanticCompare::WARNING:
                $message = '!!';
                break;
            case SemanticCompare::NOTICE:
                $message = '!';
                break;

            case SemanticCompare::ERROR:
                $message = '!!!';
                break;
        }

        return $message;
    }
}