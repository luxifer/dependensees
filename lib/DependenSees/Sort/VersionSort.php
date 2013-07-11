<?php

namespace DependenSees\Sort;

use Composer\Package\LinkConstraint\VersionConstraint;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionSort
{
    protected $versions;

    public function __construct(array $versions)
    {
        $this->versions = $versions;

        return $this;
    }

    public function sort()
    {
        usort($this->versions, array('self', 'compare'));

        return $this->versions;
    }

    public function compare($a, $b)
    {
        $constraint = new VersionConstraint('>', '');

        return $constraint->versionCompare($a['version'], $b['version'], '<', true);
    }
}