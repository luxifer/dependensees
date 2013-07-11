<?php

namespace DependenSees\Sort;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionSort
{
    protected $versions;
    protected $constraint;

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
        return version_compare($a['version'], $b['version'], '<');
    }
}