<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\{
    Console\Interfaces\Command, STDIO\Terminal
};
use Psr\EventDispatcher\EventDispatcherInterface;

class Application extends Utils\ListItem implements Command {

    /** @var string */
    private $name;

    /** @var string */
    private $help;

    /** @var Terminal */
    private $term;

    /** @var Command */
    private $defaultCommand;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var bool */
    private $autoExit = true;

    /** @var ArgumentParser */
    private $parser;

    public function __construct(string $name = null, string $help = null) {
        $this->name = $name ?? basename($_SERVER['argv'][0]);
        $this->help = $help ?? '';
        $this->parser = new ArgumentParser();
    }

    /** {@inheritdoc} */
    protected function itemInstanceOf(): string {
        return Command::class;
    }

}
