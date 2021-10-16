<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\JsonableTrait;
use Tolkam\Layers\Base\Domain\Value\ValueException;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;
use Tolkam\Utils\Arr;

class ArrayValue implements ValueInterface, IteratorAggregate, ArrayAccess
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
    public function get(string $path = null, $def = null, string $sep = '.'): mixed
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
    public static function fromString(string $str): static
    {
        return new static(self::jsonDecode($str));
    }
    
    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
    
    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->get($name) !== null;
    }
    
    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->value);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->value[$offset]);
    }
    
    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->value[$offset] ?? null;
    }
    
    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new ValueException('Value is read-only');
    }
    
    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new ValueException('Value is read-only');
    }
}
