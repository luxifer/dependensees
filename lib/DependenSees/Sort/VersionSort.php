<?php

namespace DependenSees\Sort;

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

    public static function compare($a, $b)
    {
        $a = new \DateTime($a['time']);
        $b = new \DateTime($b['time']);

        if ($a == $b) {
            return 0;
        }

        return ($a > $b) ? -1 : 1;
    }
}