<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\Console\Utils\ListItem;

class ArgumentList extends ListItem {

    /** {@inheritdoc} */
    protected function itemInstanceOf(): string {
        return Argument::class;
    }

    /**
     * Adds an Argument
     *
     * @param string $name Argument name
     * @param string $help Argument help message
     * @param string $short Short flag
     * @param string $long Long Flag
     * @param string $type Value Type
     * @param bool $nullable can be null
     * @return Argument
     */
    public function addArgument(string $name, string $help, string $short = null, string $long = null, string $type = Argument::TYPE_BOOL, bool $nullable = true): Argument {
        $arg = Argument::create($name, $help);
        $arg->setType($type);
        if (!$nullable) $arg->isRequired();
        $short && $arg->setShort($short);
        $long && $arg->setLong($long);
        $this->add($arg);
        return $arg;
    }

    /**
     * Add an Argument
     *
     * @param Argument $argument
     * @return static
     */
    public function add(Argument $argument) {
        $this->offsetSet($argument->getName(), $argument);
        return $this;
    }

    /**
     * Checks if argument exists
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool {
        return $this->offsetExists($name);
    }

    /**
     * Get Argument by name
     *
     * @param string $name
     * @return Argument|null
     */
    public function get(string $name): ?Argument {
        return $this->offsetGet($name) ?? null;
    }

}
