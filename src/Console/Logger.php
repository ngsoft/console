<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\{
    Console\Interfaces\Verbosity, Console\Outputs\Output, STDIO\Outputs\StreamOutput
};
use Psr\Log\{
    LoggerInterface, LoggerTrait, self
};

final class Logger extends self implements LoggerInterface, Verbosity {

    use LoggerTrait;

    private const MAP = [
        self::EMERGENCY => self::VERBOSITY_NORMAL,
        self::ALERT => self::VERBOSITY_NORMAL,
        self::CRITICAL => self::VERBOSITY_NORMAL,
        self::ERROR => self::VERBOSITY_NORMAL,
        self::WARNING => self::VERBOSITY_NORMAL,
        self::NOTICE => self::VERBOSITY_VERBOSE,
        self::INFO => self::VERBOSITY_VERY_VERBOSE,
        self::DEBUG => self::VERBOSITY_DEBUG,
    ];

    /** @var Output */
    private $output;

    /** @var int */
    private $verbosity = self::VERBOSITY_NORMAL;

    public function __construct(Output $output = null) {

        $this->output = $output ?? new Output();
    }

    public function log($level, $message, array $context = []) {

        $method = 'write';
    }

    /**
     * Get Verbosity level
     *
     * @return int
     */
    public function getVerbosity(): int {
        return $this->verbosity;
    }

    /**
     *
     * @param int $verbosity
     */
    public function setVerbosity(int $verbosity) {
        $this->verbosity = $verbosity;
    }

}
