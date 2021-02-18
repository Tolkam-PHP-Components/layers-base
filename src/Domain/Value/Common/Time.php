<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use DateTimeImmutable;
use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\ValueException;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

class Time implements ValueInterface
{
    use EqualityTrait;
    
    /**
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $value;
    
    /**
     * @param string|int $input
     */
    public function __construct($input)
    {
        if ((!is_string($input) && !is_int($input))) {
            throw new ValueException('Input time must be integer or string');
        }
        
        // if input looks like timestamp, make it parsable
        if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
            $input = '@' . $input;
        }
        
        // try to parse into DateTimeImmutable
        if (!$this->value = new DateTimeImmutable($input)) {
            throw new ValueException(sprintf(
                'Unable to create time from a given input "%s"', $input
            ));
        }
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str): self
    {
        return new self($str);
    }
    
    /**
     * Creates instance with current time
     *
     * @return Time
     */
    public static function create()
    {
        return new self(time());
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string) $this->value->getTimestamp();
    }
    
    /**
     * Formats to string
     *
     * @param string $format
     *
     * @return string
     */
    public function format(string $format)
    {
        return $this->toDateTime()->format($format);
    }
    
    /**
     * Formats to MySQL DATETIME
     *
     * @return string
     */
    public function toMysqlDatetime()
    {
        return $this->format('Y-m-d H:i:s.u');
    }
    
    /**
     * @inheritDoc
     */
    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
    
    /**
     * @return DateTimeImmutable
     */
    public function toDateTime(): DateTimeImmutable
    {
        return $this->value();
    }
}
