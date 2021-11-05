<?php

declare(strict_types=1);

namespace NGSOFT\Console\Commands;

use NGSOFT\{
    Console\Application, Console\Argument, Console\Interfaces\Command, Console\Traits\BasicCommand, STDIO
};

class Help implements Command {

    use BasicCommand;

    /** @var Application */
    protected $app;

    public function __construct(Application $app) {

        $this->app = $app;
        $this->name = 'help';
        $this->help = 'This help screen';
        $this->arguments->add(Argument::create('command', 'Command name.')->isString());
    }

    /** {@inheritdoc} */
    public function execute($arguments, STDIO $io): int {
        $command = $this->app;
        if ($commandName = $arguments['command']) {
            $command = $this->app[$commandName] ?? $this->app;
        }

        $this->render($command, $io);
        return self::COMMAND_SUCCESS;
    }

    protected function render(Command $command, STDIO $io) {

    }

}
