<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

class IntegerValue implements ValueInterface
{
    use EqualityTrait;
    
    /**
     * @var int
     */
    protected int $value;
    
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = (int) $value;
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
    public function value(): int
    {
        return $this->value;
    }
}
