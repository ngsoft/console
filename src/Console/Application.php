<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\{
    Console\Commands\Help, Console\Interfaces\Command, Console\Interfaces\Verbosity, STDIO, STDIO\Terminal
};
use Psr\EventDispatcher\EventDispatcherInterface;

class Application implements Command {

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

    /** @var STDIO */
    private $io;

    public function __construct(string $name = null, string $help = null, STDIO $stdio = null) {
        $this->name = $name ?? basename($_SERVER['argv'][0]);
        $this->help = $help ?? '';
        $this->parser = new ArgumentParser();

        $stdio = $stdio ?? STDIO::create();
        $this->io = $stdio;
        $this->term = $stdio->getTerminal();
        $this->setup();
    }

    private function setup() {
        //set up global arguments
        $this->arguments
                ->add(Argument::create('command', 'Command to run')->isString())
                ->add(Argument::create('help', 'This help screen.', '-h', '--help')->isBool())
                ->add(Argument::create('verbose', 'Verbose.', '-v', '--verbose')->isInt()->setValue(Verbosity::VERBOSITY_NORMAL))
                ->add(Argument::create('quiet', 'Set Verbosity to quiet.', '-q', '--quiet')->isBool());

        //set up commands
        $this->add(new Help($this));
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
        if ($isDefault || !$this->defaultCommand) $this->defaultCommand = $command;
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

    /**
     * Executes the Application
     *
     * @param ?array $args
     * @return int
     */
    public function run(array $args = null): int {
        if (is_null($args)) {
            $args = $_SERVER['argv'];
            array_shift($args);
        }


        //command parser


        $result = $this->execute($args, $this->io);
        if ($this->autoExit) exit($result);
        return $result;
    }

    ////////////////////////////   Interfaces   ////////////////////////////

    /** {@inheritdoc} */
    protected function itemInstanceOf(): string {
        return Command::class;
    }

    /** {@inheritdoc} */
    public function execute(array $arguments, STDIO $io): int {

        $commandName = $arguments['command'];
        if ($arguments['help']) {
            return $this['help']->execute(['command' => $commandName], $io);
        }
        if (!$commandName) $command = $this->defaultCommand;
        else $command = $this[$commandName];
        if (!$command) return self::COMMAND_NOT_FOUND;
        return $command->execute($arguments, $io);
    }

}
