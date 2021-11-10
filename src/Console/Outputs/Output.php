<?php

declare(strict_types=1);

namespace NGSOFT\Console\Outputs;

use NGSOFT\{
    STDIO, STDIO\Interfaces\Formatter, STDIO\Outputs\StreamOutput
};

final class Output {

    /** @var Formatter */
    private $formatter;

    /** @var StreamOutput */
    private $output;

    /** @var StreamOutput */
    private $errorOutput;

    public function __construct(STDIO $stdio = null) {
        $stdio = $stdio ?? STDIO::create();
        $this->formatter = $stdio->getFormatter();
        $this->output = $stdio->getOutput();
        $this->errorOutput = $stdio->getErrorOutput();
    }

    /** {@inheritdoc} */
    public function write(string $message) {
        $this->output->write($this->formatter->format($message));
    }

    /**
     * Write a line
     *
     * @param string $message
     */
    public function writeln(string $message) {
        $this->write($message . "\n");
    }

    /** {@inheritdoc} */
    public function writeError(string $message) {
        $this->errorOutput->write($this->formatter->format($message));
    }

}
