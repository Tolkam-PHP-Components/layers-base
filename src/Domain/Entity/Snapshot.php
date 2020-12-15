<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Entity;

use ArrayIterator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionNamedType;
use Tolkam\Base\Domain\Value\ValueInterface;

/**
 * Base snapshot class with helper methods
 *
 * Allows snapshot to be constructed from array only and requires to be valid
 * Ignores untyped properties or properties that are not values or sub-snapshots from import/export
 * Automatically creates values and sub-snapshots from supplied data based on property type-hints
 *
 * @package Tolkam\Base\Domain
 */
abstract class Snapshot implements SnapshotInterface, IteratorAggregate
{
    /**
     * Allow new instances with fromArray() only
     */
    final protected function __construct()
    {
        // use init method for setting defaults, etc.
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }
    
    /**
     * @inheritDoc
     */
    public static function fromArray(array $arr): static
    {
        $i = new static;
        $i->autoHydrate($arr);
        $i->validate();
        
        return $i;
    }
    
    /**
     * @inheritDoc
     */
    public function toArray(bool $cast = true): array
    {
        $arr = [];
        
        foreach (array_keys(get_object_vars($this)) as $prop) {
            if (!property_exists($this, $prop)) {
                throw new SnapshotException(sprintf(
                    'Unable to export an unknown property "%s"',
                    $prop
                ));
            }
            
            $value = $this->{$prop};
            
            if ($cast) {
                $value = $value instanceof SnapshotInterface ? $value->toArray() : $value;
                $value = $value instanceof ValueInterface ? $value->__toString() : $value;
            }
            
            $arr[$prop] = $value;
        }
        
        return $arr;
    }
    
    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (!property_exists($this, $name)) {
            throw new SnapshotException(sprintf(
                'Failed to get value of unknown property "%s"',
                $name
            ));
        }
        
        return $this->{$name};
    }
    
    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new SnapshotException('Dynamic properties are not allowed');
        }
        
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
        
        $this->{$name} = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this);
    }
    
    /**
     * Automatically hydrates snapshot from array
     *
     * @param array $source
     *
     * @return static
     */
    protected function autoHydrate(array $source): static
    {
        $errorMessage = 'Failed to hydrate snapshot: ';
        $self = new ReflectionClass($this);
        
        foreach ($source as $propName => $value) {
            
            if (!$self->hasProperty($propName)) {
                throw new SnapshotException(
                    sprintf($errorMessage . 'unknown property "%s"', $propName)
                );
            }
            
            $prop = $self->getProperty($propName);
            $prop->setAccessible(true);
            
            if ($prop->isPrivate()) {
                throw new SnapshotException(
                    sprintf($errorMessage . 'property "%s" must not be private', $propName)
                );
            }
            
            // ignore untyped props
            /** @var ReflectionNamedType $propType */
            if (!$propType = $prop->getType()) {
                continue;
            }
            
            // optional and null
            if ($propType->allowsNull() && is_null($value)) {
                continue;
            }
            
            // already set
            if ($prop->isInitialized($this) && !is_null($prop->getValue($this))) {
                continue;
            }
            
            // set built-in types as-is
            if ($propType->isBuiltin()) {
                $this->__set($propName, $value);
                continue;
            }
            
            $propClass = $propType->getName();
            
            // value is Value or Snapshot
            if ($value instanceof ValueInterface || $value instanceof SnapshotInterface) {
                $this->__set($propName, $value);
                continue;
            }
            
            // value is array and prop is Snapshot
            if (is_array($value) && is_subclass_of($propClass, SnapshotInterface::class)) {
                /** @var SnapshotInterface $propClass */
                $this->__set($propName, $propClass::fromArray($value));
                continue;
            }
            
            // value cast-able to string and prop is Value
            if (is_scalar($value) && is_subclass_of($propClass, ValueInterface::class)) {
                $this->__set($propName, $propClass::fromString((string) $value));
                continue;
            }
            
            throw new SnapshotException(sprintf(
                $errorMessage . '"%s" is of unknown type %s',
                $propName,
                $propClass
            ));
        }
        
        return $this;
    }
    
    /**
     * Validates snapshot to have all typed properties to be initialized
     *
     * @return void
     */
    protected function validate()
    {
        $properties = (new ReflectionClass($this))->getProperties();
        
        foreach ($properties as $property) {
            $property->setAccessible(true);
            if (!$property->isInitialized($this)) {
                throw new SnapshotException(sprintf(
                    'Invalid snapshot state: property "%s" is required',
                    $property->getName()
                ));
            }
            $property->setAccessible(false);
        }
    }
}
