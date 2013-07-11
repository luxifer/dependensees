<?php

namespace DependenSees;

use Symfony\Component\Console\Application as BaseApplication;
use DependenSees\Command\CheckCommand;
use Symfony\Component\Console\Input\InputInterface;
use Composer\IO\ConsoleIO;
use Composer\Factory;
use Composer\Json\JsonValidationException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class Application extends BaseApplication
{
    protected $io;
    protected $composer;

    public function __construct()
    {
        parent::__construct('DependenSees', DependenSees::VERSION);
    }

    protected function getCommandName(InputInterface $input)
    {
        // Retourne le nom de votre commande.
        return 'dependensees';
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->io = new ConsoleIO($input, $output, $this->getHelperSet());

        return parent::doRun($input, $output);
    }

    protected function getDefaultCommands()
    {
        // Conserve les commandes par défaut du noyau pour avoir la
        // commande HelpCommand en utilisant l'option --help
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new CheckCommand();

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // efface le premier argument, qui est le nom de la commande
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * @param  bool                    $required
     * @throws JsonValidationException
     * @return \Composer\Composer
     */
    public function getComposer($required = true)
    {
        if (null === $this->composer) {
            try {
                $this->composer = Factory::create($this->io);
            } catch (\InvalidArgumentException $e) {
                if ($required) {
                    $this->io->write($e->getMessage());
                    exit(1);
                }
            } catch (JsonValidationException $e) {
                $errors = ' - ' . implode(PHP_EOL . ' - ', $e->getErrors());
                $message = $e->getMessage() . ':' . PHP_EOL . $errors;
                throw new JsonValidationException($message);
            }

        }

        return $this->composer;
    }
}