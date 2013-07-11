<?php

namespace DependenSees\Sort;

use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Package\Version\VersionParser;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionSort
{
    protected $parser;

    public function __construct()
    {
        $this->parser = new VersionParser();

        return $this;
    }

    public function nameSort($versions)
    {
        usort($versions, array('self', 'compareVersion'));

        return $versions;
    }

    public function compareVersion($a, $b)
    {
        $constraint = new VersionConstraint('>', '');

        return $constraint->versionCompare($this->parser->normalize($a['version']), $this->parser->normalize($b['version']), '<', true);
    }
}