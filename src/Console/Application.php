<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\{
    Console\Interfaces\Command, Console\Utils\ListItem, STDIO, STDIO\Terminal
};
use Psr\EventDispatcher\EventDispatcherInterface;

class Application extends ListItem implements Command {

    /** @var string */
    private $name;

    /** @var string */
    private $help;

    /** @var Terminal */
    private $term;

    /** @var Command */
    private $defaultCommand;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var bool */
    private $autoExit = true;

    /** @var ArgumentParser */
    private $parser;

    /** @var Argumentlist */
    private $arguments;

    public function __construct(string $name = null, string $help = null) {
        $this->name = $name ?? basename($_SERVER['argv'][0]);
        $this->help = $help ?? '';
        $this->parser = new ArgumentParser();
        $this->arguments = new ArgumentList();
    }

    ////////////////////////////   Setters   ////////////////////////////

    /**
     * Set Event Dispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     * @return static
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher) {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * Set Auto Exit flag
     *
     * @param bool $autoExit
     * @return static
     */
    public function setAutoExit(bool $autoExit) {
        $this->autoExit = $autoExit;
        return $this;
    }

    ////////////////////////////   Utils   ////////////////////////////

    /**
     * Add a Command
     *
     * @param Command $command
     * @param bool $isDefault
     * @return static
     */
    public function add(Command $command, bool $isDefault = false) {
        $this->storage[$command->getName()] = $command;
        if ($isDefault) $this->defaultCommand = $command;
        return $this;
    }

    /**
     * Add multiple commands
     *
     * @param Command[] $commands
     * @return $this
     */
    public function addCommands(array $commands) {
        foreach ($commands as $command) $this->add($command);
        return $this;
    }

    /**
     * Checks if command exists
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool {
        return $this->offsetExists($name);
    }

    ////////////////////////////   Interfaces   ////////////////////////////

    /** {@inheritdoc} */
    protected function itemInstanceOf(): string {
        return Command::class;
    }

    /** {@inheritdoc} */
    public function execute(array $arguments, STDIO $io): int {





        return self::COMMAND_SUCCESS;
    }

    /** {@inheritdoc} */
    public function getArguments(): ArgumentList {
        return $this->arguments;
    }

    /** {@inheritdoc} */
    public function getHelp(): string {
        return $this->help;
    }

    /** {@inheritdoc} */
    public function getName(): string {
        return $this->name;
    }

}
