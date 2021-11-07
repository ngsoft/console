<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\Console\{
    Interfaces\Verbosity, Outputs\Output
};
use Psr\Log\{
    LoggerAwareTrait, LoggerInterface, LoggerTrait, LogLevel
};

/**
 * Logger that outputs log message using the verbosity Flag
 * and can use an external logger to log messages
 */
final class Logger extends LogLevel implements LoggerInterface, Verbosity {

    use LoggerTrait,
        LoggerAwareTrait;

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

    /** {@inheritdoc} */
    public function log($level, $message, array $context = []) {
        if ($this->verbosity >= (self::MAP[$level] ?? self::VERBOSITY_NORMAL)) {
            $str = sprintf(
                    "<%s>[%s]</%s> %s\n",
                    $level, strtoupper($level), $level,
                    $message
            );
            $this->output->write($str);
        }

        if ($this->logger) $this->logger->log($str, $message, $context);
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
     * Set Verbosity level
     * 
     * @param int $verbosity
     */
    public function setVerbosity(int $verbosity) {
        $this->verbosity = $verbosity;
    }

}
