<?php

namespace DependenSees\Parser;

use Composer\Package\CompletePackage;
use Composer\Package\Version\VersionParser;
use Guzzle\Http\Client;
use DependenSees\Sort\VersionSort;
use DependenSees\Trim\VersionTrim;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class VersionsParser
{
    protected $trim;
    protected $sort;
    protected $client;

    public function __construct()
    {
        $this->sort = new VersionSort();
        $this->trim = new VersionTrim();
        $this->client = new Client('https://packagist.org');
    }

    public function latest(CompletePackage $package)
    {
        $response = json_decode($this->client->get('/packages/'.$package->getName().'.json')->send()->getBody(), true);
        $versions = $response['package']['versions'];
        $stability = VersionParser::parseStability($package->getPrettyVersion());
        $versions = $this->trim->trim($versions, $stability);
        $versions = $this->sort->nameSort($versions);
        
        return array_shift($versions);
    }
}