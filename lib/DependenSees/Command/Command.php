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
}