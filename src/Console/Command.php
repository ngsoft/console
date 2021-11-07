<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use InvalidArgumentException;
use NGSOFT\{
    Console\Events\ConsoleEvent, Console\Interfaces\ExitCodes, STDIO
};

abstract class Command implements ExitCodes {

    protected const VALID_COMMAND_NAME_REGEX = '/^[a-z][\w\-\:]*$/i';

    /** @var Argument[] */
    protected $arguments = [];

    /** @var string */
    protected $name;

    /** @var string */
    protected $help;

    public function __construct(string $name, string $help) {
        if (!preg_match(self::VALID_COMMAND_NAME_REGEX, $name)) {
            throw new InvalidArgumentException('Invalid command name ' . $name);
        }
        $this->name = $name;
        $this->help = $help;
    }

    ////////////////////////////   Getters   ////////////////////////////

    /**
     * Get Command Name
     *
     * @return string
     */
    final public function getName(): string {
        return $this->name;
    }

    /**
     * Get Command Help message
     *
     * @return string
     */
    final public function getHelp(): string {
        return $this->help;
    }

    /**
     * Get Command Arguments
     *
     * @return Argument[]
     */
    final public function getArguments(): array {
        return $this->arguments;
    }

    ////////////////////////////   Abstract   ////////////////////////////

    /**
     * Executes the command
     *
     * @param array<string,mixed> $arguments Parsed arguments
     * @param STDIO $io
     *
     * @return int Exit Code to return to the shell
     */
    abstract public function execute(array $arguments, STDIO $io): int;

    ////////////////////////////   Interfaces   ////////////////////////////


    public function dispatch(ConsoleEvent $event): ConsoleEvent {

    }

}
