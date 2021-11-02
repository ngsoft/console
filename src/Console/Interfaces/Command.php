<?php

declare(strict_types=1);

namespace NGSOFT\Console\Interfaces;

use NGSOFT\{
    Console\Argument, STDIO
};

/**
 * @link https://tldp.org/LDP/abs/html/exitcodes.html
 */
interface Command extends ExitCodes {

    public const VALID_COMMAND_NAME_REGEX = '/^[a-z][a-z0-9\_\-\:]+$/i';

    /**
     * Get Command Name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get Command Help message
     *
     * @return string
     */
    public function getHelp(): string;

    /**
     * Get Command Options
     *
     * @return Argument[]
     */
    public function getArguments(): array;

    /**
     * Executes the command
     *
     * @param array $arguments
     * @param STDIO $io
     *
     * @return int Exit Code to return to the shell
     */
    public function execute(array $arguments, STDIO $io): int;
}
