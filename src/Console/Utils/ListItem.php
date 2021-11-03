<?php

declare(strict_types=1);

namespace NGSOFT\Console\Utils;

use ArrayAccess,
    Countable,
    IteratorAggregate,
    OutOfBoundsException,
    RuntimeException;

abstract class ListItem implements Countable, IteratorAggregate, ArrayAccess {

    /** @var object[] */
    protected $storage = [];

    /**
     * Accepted instances for items
     */
    abstract protected function itemInstanceOf(): string;

    ////////////////////////////   Interfaces   ////////////////////////////

    /** {@inheritdoc} */
    public function count() {
        return count($this->storage);
    }

    /**
     * @var \Generator<string,object>
     */
    public function getIterator() {
        foreach ($this->storage as $name => $instance) yield $name => $instance;
    }

    /** {@inheritdoc} */
    public function offsetExists($offset) {
        return isset($this->storage[$offset]);
    }

    /** {@inheritdoc} */
    public function &offsetGet($offset) {
        $value = $this->storage[$offset] ?? null;
        return $value;
    }

    /** {@inheritdoc} */
    public function offsetSet($offset, $value) {
        $className = $this->itemInstanceOf();
        if (!($value instanceof $className)) {
            throw new RuntimeException('Invalid value, not an instance of ' . $className);
        }
        if (!is_string($offset)) {
            throw new OutOfBoundsException('Invalid key "' . is_null($offset) ? 'null' : $offset . '"');
        }
        $this->storage[$offset] = $value;
    }

    /** {@inheritdoc} */
    public function offsetUnset($offset) {
        unset($this->storage[$offset]);
    }

    ////////////////////////////   Magic Methods   ////////////////////////////

    /** {@inheritdoc} */
    public function &__get($name) {
        $value = $this->offsetGet($name);
        return $value;
    }

    /** {@inheritdoc} */
    public function __isset($name) {
        return $this->offsetExists($name);
    }

    /** {@inheritdoc} */
    public function __set($name, $value) {
        $this->offsetSet($name, $value);
    }

    /** {@inheritdoc} */
    public function __unset($name) {
        $this->offsetUnset($name);
    }

    /** {@inheritdoc} */
    public function __debugInfo() {
        return $this->storage;
    }

}
