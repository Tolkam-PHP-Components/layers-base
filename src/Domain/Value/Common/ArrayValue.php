<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value\Common;

use Tolkam\Utils\Arr;
use Tolkam\Base\Domain\Value\EqualityTrait;
use Tolkam\Base\Domain\Value\JsonableTrait;
use Tolkam\Base\Domain\Value\ValueInterface;

class ArrayValue implements ValueInterface
{
    use EqualityTrait, JsonableTrait;
    
    /**
     * @var array
     */
    protected array $value;
    
    /**
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value;
    }
    
    /**
     * Sets value by path
     *
     * @param string $path
     * @param        $value
     *
     * @return ArrayValue
     */
    public function with(string $path, $value): self
    {
        $arr = $this->value;
        
        Arr::set($arr, $path, $value);
        
        return new static($arr);
    }
    
    /**
     * Gets value by path
     *
     * @param string|null $path
     * @param null        $def
     * @param string      $sep
     *
     * @return mixed
     */
    public function get(string $path = null, $def = null, $sep = '.')
    {
        return Arr::get($this->value, $path, $def, $sep);
    }
    
    /**
     * @inheritDoc
     */
    public function value(): array
    {
        return $this->value;
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return self::jsonEncode($this->value, JSON_FORCE_OBJECT);
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str)
    {
        return new static(self::jsonDecode($str));
    }
}
