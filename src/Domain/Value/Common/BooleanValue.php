<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value\Common;

use Tolkam\Base\Domain\Value\EqualityTrait;
use Tolkam\Base\Domain\Value\ValueInterface;

class BooleanValue implements ValueInterface
{
    use EqualityTrait;
    
    /**
     * @var bool
     */
    protected bool $value;
    
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = (bool) $value;
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str)
    {
        return new static($str);
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
    
    /**
     * @inheritDoc
     */
    public function value(): bool
    {
        return $this->value;
    }
}
