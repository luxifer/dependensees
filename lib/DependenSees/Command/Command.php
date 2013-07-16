<?php

namespace DependenSees\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use DependenSees\Application;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class Command extends BaseCommand
{
    protected $composer;

    protected $logo;

    public function __construct()
    {
        $this->logo = <<<EOS
        ____                            __          _____               
       / __ \___  ____  ___  ____  ____/ /__  ____ / ___/___  ___  _____
      / / / / _ \/ __ \/ _ \/ __ \/ __  / _ \/ __ \\\__ \/ _ \/ _ \/ ___/
     / /_/ /  __/ /_/ /  __/ / / / /_/ /  __/ / / /__/ /  __/  __(__  ) 
    /_____/\___/ .___/\___/_/ /_/\__,_/\___/_/ /_/____/\___/\___/____/  
              /_/                                                               
EOS;

    parent::__construct();
        
    }

  /**
     * @param  bool              $required
     * @throws \RuntimeException
     * @return Composer
     */
    public function getComposer($required = true)
    {
        if (null === $this->composer) {
            $application = $this->getApplication();
            if ($application instanceof Application) {
                /* @var $application    Application */
                $this->composer = $application->getComposer($required);
            } elseif ($required) {
                throw new \RuntimeException(
                    'Could not create a Composer\Composer instance, you must inject '.
                    'one if this command is not used with a Composer\Console\Application instance'
                );
            }
        }

        return $this->composer;
    }

    public function getRoot()
    {
        return $this->getApplication()->getRoot();
    }
}