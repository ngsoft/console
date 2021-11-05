<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use InvalidArgumentException,
    RuntimeException;
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
    public const TYPE_ARRAY = 'array';

    /**
     * Argument name validation
     */
    protected const NAME_REGEX = '/^[a-z][\w\-]*$/i';

    /**
     * Accepts only letters, lowercase or uppercase
     * as an argument value can be a negative integer
     */
    protected const SHORT_REGEX = '/^\-[a-z]$/i';

    /**
     * Only lowercase chars with - and _
     */
    protected const LONG_REGEX = '/^[\-]{2}[a-z][\w\-]+$/';
    protected const TYPES = [
        self::TYPE_STRING, self::TYPE_INT, self::TYPE_FLOAT, self::TYPE_BOOL, self::TYPE_ARRAY, 'null'
    ];

    /** @var string */
    protected $name;

    /** @var string */
    protected $help;

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

    /** @var ?callable */
    protected $transformCallable;

    /** @var ?callable */
    protected $validationCallable;

    ////////////////////////////   Initialization   ////////////////////////////

    /**
     * Creates a new Argument
     * @param string $name Argument help
     * @param string $help Message to display with help command
     * @return static
     */
    public static function create(string $name, string $help) {
        return new static($name, $help);
    }

    /**
     * Creates a new Argument
     * @param string $name
     * @param string $help
     */
    public function __construct(string $name, string $help) {
        if (false === preg_match(self::NAME_REGEX, $name)) {
            throw new InvalidArgumentException('Invalid name ' . $name);
        }
        $this->name = $name;
        $this->help = $help;
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
     * Get Help message
     * @return string
     */
    public function getHelp(): string {
        return $this->help;
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
     * Get Value Type
     *
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Argument can be null?
     *
     * @return bool
     */
    public function getNullable(): bool {
        return $this->nullable;
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
     * Set a callback to transform parsed value
     *
     * @param callable $transformCallable
     * @return static
     */
    public function setTransformCallable(callable $transformCallable) {
        $this->transformCallable = $transformCallable;
        return $this;
    }

    /**
     * Set a callback to validate a value
     *
     * @param callable $validationCallable
     * @return static
     */
    public function setValidationCallable(callable $validationCallable) {
        $this->validationCallable = $validationCallable;
        return $this;
    }

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

    ////////////////////////////   Internals   ////////////////////////////

    /**
     * Transform the value
     *
     * @internal
     * @param mixed $value
     * @return mixed
     */
    public function transformValue($value) {

        if (is_callable($this->transformCallable)) {
            return call_user_func($this->transformCallable, $value);
        }

        if (is_numeric($value)) {
            $value = preg_match('/^\d*\.\d+$/', $value) !== false ? floatval($value) : intval($value);
            if ($this->type == self::TYPE_INT) $value = intval($value);
            elseif ($this->type == self::TYPE_FLOAT) $value = floatval($value);
        }
        if (
                $this->type == self::TYPE_ARRAY and
                !is_null($value)
        ) $value = is_array($value) ? $value : [$value];
        if ($this->type == self::TYPE_BOOL) return $value === true;

        return $value;
    }

    /**
     * Validate the value
     *
     * @internal
     * @param type $value
     * @return bool
     */
    public function validateValue($value): bool {
        if (is_callable($this->validationCallable)) {
            $return = call_user_func($this->validationCallable, $value);
            if (!is_bool($return)) {
                throw new RuntimeException(sprintf('Invalid return value for validation callback in Argument "%s", boolean requested but %s given', $this->name, get_debug_type($retval)));
            }
            return $return;
        }
        if (is_null($value)) return $this->nullable;
        $fnc = sprintf('is_%s', $this->type);
        return call_user_func($fnc, $value);
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
     * Can be used multiple times
     *
     * @return static
     */
    public function isArray() {
        $this->type = self::TYPE_ARRAY;
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
