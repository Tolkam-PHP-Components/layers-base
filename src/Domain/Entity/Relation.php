<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

use ReflectionClass;
use ReflectionNamedType;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

abstract class Relation implements RelationInterface
{
    /**
     * Allow new instances with fromArray() only
     */
    final protected function __construct()
    {
    }
    
    /**
     * @inheritDoc
     */
    public static function fromArray(array $source): static
    {
        $i = new static;
        $i->hydrate($source);
        $i->validate();
        
        return $i;
    }
    
    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (!property_exists($this, $name)) {
            throw new RelationException(sprintf(
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
            throw new RelationException('Dynamic properties are not allowed');
        }
        
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
        
        $this->{$name} = $value;
    }
    
    /**
     * Automatically hydrates from array
     *
     * @param array $source
     *
     * @return static
     */
    protected function hydrate(array $source): static
    {
        $errorMessage = 'Failed to hydrate relation: ';
        $self = new ReflectionClass($this);
        
        foreach ($source as $propName => $value) {
            
            if (!$self->hasProperty($propName)) {
                throw new RelationException(
                    sprintf($errorMessage . 'unknown property "%s"', $propName)
                );
            }
            
            $prop = $self->getProperty($propName);
            $prop->setAccessible(true);
            
            if ($prop->isPrivate()) {
                throw new RelationException(
                    sprintf($errorMessage . 'property "%s" must not be private', $propName)
                );
            }
            
            // ignore untyped props
            /** @var ReflectionNamedType $propType */
            if (!$propType = $prop->getType()) {
                continue;
            }
            
            // already set
            if ($prop->isInitialized($this) && !is_null($prop->getValue($this))) {
                continue;
            }
            
            // value is Value
            if ($value instanceof ValueInterface) {
                $this->__set($propName, $value);
                continue;
            }
            
            // value is scalar and prop is Value
            $propClass = $propType->getName();
            if (is_scalar($value) && is_subclass_of($propClass, ValueInterface::class)) {
                $this->__set($propName, $propClass::fromString((string) $value));
                continue;
            }
            
            throw new RelationException(sprintf(
                $errorMessage . '"%s" is of unknown type %s',
                $propName,
                $propClass
            ));
        }
        
        return $this;
    }
    
    /**
     * Ensures all typed properties have been initialized
     *
     * @return void
     */
    protected function validate()
    {
        $properties = (new ReflectionClass($this))->getProperties();
        
        foreach ($properties as $property) {
            $property->setAccessible(true);
            if (!$property->isInitialized($this)) {
                throw new RelationException(sprintf(
                    'Invalid relation state: property "%s" is required',
                    $property->getName()
                ));
            }
            $property->setAccessible(false);
        }
    }
}
