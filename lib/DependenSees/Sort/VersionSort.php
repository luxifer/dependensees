<?php

namespace DependenSees\Sort;

use Composer\Package\LinkConstraint\VersionConstraint;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionSort
{
    public function __construct()
    {
        return $this;
    }

    public function sort($versions)
    {
        usort($versions, array('self', 'compare'));

        return $versions;
    }

    public function compare($a, $b)
    {
        $constraint = new VersionConstraint('>', '');

        return $constraint->versionCompare($a['version'], $b['version'], '<', true);
    }
}