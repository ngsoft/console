<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use InvalidArgumentException;
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

    private const OUTPUT_NORMAL = 1;
    private const OUTPUT_ERROR = 2;

    /**
     * Map verbosity with LogLevel
     */
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

    /**
     * Outputs to use
     */
    private const OUTPUTS = [
        self::EMERGENCY => self::OUTPUT_ERROR,
        self::ALERT => self::OUTPUT_ERROR,
        self::CRITICAL => self::OUTPUT_ERROR,
        self::ERROR => self::OUTPUT_ERROR,
        self::WARNING => self::OUTPUT_NORMAL,
        self::NOTICE => self::OUTPUT_NORMAL,
        self::INFO => self::OUTPUT_NORMAL,
        self::DEBUG => self::OUTPUT_NORMAL,
    ];

    /** @var Output */
    private $output;

    /** @var int */
    private $verbosity = self::VERBOSITY_NORMAL;

    /** @var bool */
    private $silent = false;

    public function __construct(Output $output = null) {
        $this->output = $output ?? new Output();
    }

    /** {@inheritdoc} */
    public function log($level, $message, array $context = []) {

        if (!in_array($level, array_keys(self::MAP))) {
            throw new InvalidArgumentException('Invalid log level ' . $level);
        }


        if ($this->verbosity >= (self::MAP[$level] ?? self::VERBOSITY_NORMAL)) {
            $str = sprintf(
                    "<%s>[%s]</%s> %s\n",
                    $level, strtoupper($level), $level,
                    $message
            );
            if (!$this->silent) {
                if (self::OUTPUTS[$level] == self::OUTPUT_NORMAL) $this->output->write($str);
                else $this->output->writeError($str);
            }
            $this->silent = false;
        }

        if ($this->logger) $this->logger->log($level, $message, $context);
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

    /**
     * Set silent for the next log
     * Used to handle errors
     * If a logger like monolog is registered the log will be added even if silent
     *
     * @param bool $silent
     * @return static
     */
    public function setSilent(bool $silent = true) {
        $this->silent = $silent;
        return $this;
    }

}
