<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use DateTimeImmutable;
use Tolkam\Layers\Base\Domain\Rule\Time as TimeRule;
use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\RulesTrait;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

class Time implements ValueInterface
{
    use RulesTrait;
    use EqualityTrait;
    
    /**
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $value;
    
    /**
     * @param int|string $input
     */
    public function __construct(int|string $input)
    {
        self::applyRule(new TimeRule, $input);
        
        // make possible timestamp parseable
        if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
            $input = '@' . $input;
        }
        
        $this->value = new DateTimeImmutable($input);
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str): static
    {
        return new static($str);
    }
    
    /**
     * Creates instance with current time
     *
     * @return static
     */
    public static function create(): static
    {
        return new static(time());
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
    public function format(string $format): string
    {
        return $this->toDateTime()->format($format);
    }
    
    /**
     * Formats to MySQL DATETIME
     *
     * @return string
     */
    public function toMysqlDatetime(): string
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
