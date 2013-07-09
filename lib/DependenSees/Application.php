<?php

namespace DependenSees;

use Symfony\Component\Console\Application as BaseApplication;
use Dflydev\EmbeddedComposer\Core\EmbeddedComposerInterface;
use DependenSees\Command\CheckCommand;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class Application extends BaseApplication
{
    protected $embeddedComposer;

    public function __construct(EmbeddedComposerInterface $embeddedComposer)
    {
        $this->embeddedComposer = $embeddedComposer;
        parent::__construct('DependenSees', '1.0.0-dev');
    }

    protected function getCommandName(InputInterface $input)
    {
        // Retourne le nom de votre commande.
        return 'dependensees';
    }

    protected function getDefaultCommands()
    {
        // Conserve les commandes par défaut du noyau pour avoir la
        // commande HelpCommand en utilisant l'option --help
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new CheckCommand($this->embeddedComposer);

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // efface le premier argument, qui est le nom de la commande
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}