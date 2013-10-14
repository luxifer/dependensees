<?php

namespace DependenSees\Compare;

use Composer\Package\Version\VersionParser;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class SemanticCompare
{
    const ERROR   = 0;
    const WARNING = 1;
    const NOTICE  = 2;
    const SUCCESS = 3;

    protected $parser;

    public function __construct()
    {
        $this->parser = new VersionParser();
    }

    public function compare($a, $b)
    {
        $a = $this->parser->normalize($a);
        $b = $this->parser->normalize($b);
        $stability = $this->parser->parseStability($a);

        if ($stability !== 'dev') {
            $splitA = array();
            $splitB = array();
            $status = array(self::SUCCESS);

            list($splitA['major'], $splitA['minor'], $splitA['patch'], $splitA['revision']) = explode('.', $a);
            list($splitB['major'], $splitB['minor'], $splitB['patch'], $splitB['revision']) = explode('.', $b);

            $status[] = $this->compareMajor($splitA, $splitB);
            $status[] = $this->compareMinor($splitA, $splitB);
            $status[] = $this->comparePatch($splitA, $splitB);

            return min($status);
        } else {
            return $a !== $b ? self::ERROR : self::SUCCESS;
        }
    }

    protected function compareMajor($splitA, $splitB)
    {
        if ($splitA['major'] !== $splitB['major']) {
            return self::ERROR;
        }

        return self::SUCCESS;
    }

    protected function compareMinor($splitA, $splitB)
    {
        if ($splitA['major'] === $splitB['major'] && $splitA['minor'] !== $splitB['minor']) {
            return self::WARNING;
        }

        return self::SUCCESS;
    }

    protected function comparePatch($splitA, $splitB)
    {
        if (($splitA['major'] === $splitB['major'] && $splitA['minor'] === $splitB['minor']) && ($splitA['patch'] !== $splitB['patch'] || $splitA['revision'] !== $splitB['revision'])) {
            return self::NOTICE;
        }

        return self::SUCCESS;
    }
}
