<?php

namespace DependenSees\Trim;

use Composer\Package\Version\VersionParser;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionTrim
{
    public function __construct()
    {
        return $this;
    }

    public function trim($versions, $stability)
    {
        return array_filter($versions, function($item) use($stability) {
            return $stability === VersionParser::parseStability($item['version']);
        });
    }
}