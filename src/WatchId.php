<?php  declare(strict_types=1);

namespace Spagi\Watcher;

final class WatchId
{

    /** @var int */
    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function createFromInteger(int $value): self
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Argument: [{$value}] should be greater than 0");
        }

        return new static($value);
    }

    public static function createFromString(string $value): self
    {

        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException("Argument: [{$value}] is not a valid representation of a integer number");
        }

        $intValue = (int)$value;

        if ($intValue <= 0) {
            throw new InvalidArgumentException("Argument: [{$intValue}] should be greater than 0");
        }

        return new static($intValue);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}
