<?php

declare(strict_types=1);

namespace NGSOFT\Console\Traits;

trait BasicCommand {

    /** @var ArgumentList */
    protected $arguments;

    /** @var string */
    protected $name;

    /** @var string */
    protected $help;

    public function __construct(string $name, string $help) {
        if (!preg_match(Command::VALID_COMMAND_NAME_REGEX, $name)) {
            throw new InvalidArgumentException('Invalid command name "' . $name . '"');
        }
        $this->help = $help;
    }

    public function getArguments(): ArgumentList {
        return $this->arguments;
    }

    public function getHelp(): string {
        return $this->help;
    }

    public function getName(): string {
        return $this->name;
    }

}
