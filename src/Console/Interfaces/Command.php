<?php

declare(strict_types=1);

namespace NGSOFT\Console\Interfaces;

use NGSOFT\{
    Console\ArgumentList, STDIO
};

/**
 * @link https://tldp.org/LDP/abs/html/exitcodes.html
 */
interface Command extends ExitCodes {

    public const VALID_COMMAND_NAME_REGEX = '/^[a-z][\w\-\:]*$/i';

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
     * @return ArgumentList
     */
    public function getArguments(): ArgumentList;

    /**
     * Executes the command
     *
     * @param array<string,mixed> $arguments Parsed arguments
     * @param STDIO $io
     *
     * @return int Exit Code to return to the shell
     */
    public function execute(array $arguments, STDIO $io): int;
}
