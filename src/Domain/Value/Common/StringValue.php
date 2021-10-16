<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

class StringValue implements ValueInterface
{
    use EqualityTrait;
    
    /**
     * @var string
     */
    protected string $value;
    
    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str): static
    {
        return new static($str);
    }
    
    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return $this->value;
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value();
    }
    
    /**
     * Returns new instance with transformed value
     *
     * @param callable $transformer
     *
     * @return static
     */
    public function transform(callable $transformer): static
    {
        return static::fromString($transformer($this->value));
    }
}
