<?php

declare(strict_types=1);

namespace NGSOFT\Console\Interfaces;

use NGSOFT\{
    Console\Option, STDIO
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
     * Get Command Description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get Command Options
     *
     * @return Option[]
     */
    public function getOptions(): array;

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
