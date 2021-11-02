<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use InvalidArgumentException;
use function get_debug_type;

class Argument {

    /**
     * Option that doesn't require a flag
     */
    public const ANONYMOUS = 0;

    /**
     * Option that require a short flag
     */
    public const SHORT = 1;

    /**
     * Option that require a long tag
     */
    public const LONG = 2;

    /**
     * Option that require a short and a long tag
     */
    public const BOTH = 3;

    /**
     * Value type to be returned
     */
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_BOOL = 'bool';

    /**
     * Argument validation
     */
    protected const NAME_REGEX = '/^[a-z][\w\-]*$/i';
    protected const SHORT_REGEX = '/^\-[a-z0-9]$/i';
    protected const LONG_REGEX = '/^[\-]{2}[a-z][\w\-]+$/i';
    protected const TYPES = [
        self::TYPE_STRING, self::TYPE_INT, self::TYPE_FLOAT, self::TYPE_BOOL, 'null'
    ];

    /** @var string */
    protected $name;

    /** @var string */
    protected $short = '';

    /** @var string */
    protected $long = '';

    /** @var string */
    protected $type = self::TYPE_BOOL;

    /** @var bool */
    protected $nullable = true;

    /** @var mixed */
    protected $value = null;

    ////////////////////////////   Initialization   ////////////////////////////

    /**
     * Creates a new Argument
     * @param string $name
     * @return static
     */
    public static function create(string $name) {
        return new static($name);
    }

    /**
     * Creates a new Argument
     * @param string $name
     */
    public function __construct(string $name) {
        if (false === preg_match(self::NAME_REGEX, $name)) {
            throw new InvalidArgumentException('Invalid name ' . $name);
        }
        $this->name = $name;
    }

    ////////////////////////////   Getters   ////////////////////////////

    /**
     * Get argument Name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get short Argument
     *
     * @return string
     */
    public function getShort(): string {
        return $this->short;
    }

    /**
     * Get long argument
     *
     * @return string
     */
    public function getLong(): string {
        return $this->long;
    }

    /**
     * Get Argument parsed value
     *
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Get Argument Type
     *
     * @return int
     */
    public function getArgumentType(): int {
        $result = self::ANONYMOUS;
        if (!empty($this->short)) $result += self::SHORT;
        if (!empty($this->long)) $result += self::LONG;
        return $result;
    }

    ////////////////////////////   Setters   ////////////////////////////

    /**
     * Set Short flag
     *
     * @param string $short
     * @return static
     * @throws InvalidArgumentException
     */
    public function setShort(string $short) {

        if (false === preg_match(self::SHORT_REGEX, $short)) {
            throw new InvalidArgumentException('Invalid short flag ' . $short);
        }

        $this->short = $short;
        return $this;
    }

    /**
     * Set Long Flag
     *
     * @param string $long
     * @return static
     * @throws InvalidArgumentException
     */
    public function setLong(string $long) {
        if (false === preg_match(self::LONG_REGEX, $long)) {
            throw new InvalidArgumentException('Invalid long flag ' . $long);
        }
        $this->long = $long;
        return $this;
    }

    /**
     * Set Value Type
     *
     * @param string $type
     * @return static
     * @throws InvalidArgumentException
     */
    public function setType(string $type) {
        if (!in_array($type, [self::TYPE_STRING, self::TYPE_INT, self::TYPE_FLOAT, self::TYPE_BOOL])) {
            throw new InvalidArgumentException('Invalid type ' . $type);
        }
        $this->type = $type;
        return $this;
    }

    /**
     * Set Value/Default value
     *
     * @param type $value
     * @return $this
     */
    public function setValue($value) {
        $type = get_debug_type($value);
        if (!in_array($type, self::TYPES)) {
            throw new InvalidArgumentException('Invalid value type ' . $type);
        }

        $this->value = $value;
        return $this;
    }

    ////////////////////////////   Configuration   ////////////////////////////

    /**
     * Set nullable to false
     *
     * @return static
     */
    public function isRequired() {
        $this->nullable = false;
        return $this;
    }

    /**
     * Set type to string
     *
     * @return static
     */
    public function isString() {
        $this->type = self::TYPE_STRING;
        return $this;
    }

    /**
     * Set Type to int
     * @return static
     */
    public function isInt() {
        $this->type = self::TYPE_INT;
        return $this;
    }

    /**
     * Set type to boolean
     *
     * @return static
     */
    public function isBool() {
        $this->type = self::TYPE_BOOL;
        return $this;
    }

}
