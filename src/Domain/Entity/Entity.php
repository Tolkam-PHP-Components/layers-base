<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

use ReflectionClass;
use ReflectionNamedType;
use Tolkam\Layers\Base\Domain\Event\EventsAwareInterface;
use Tolkam\Layers\Base\Domain\Event\EventsAwareTrait;

abstract class Entity implements EntityInterface, EventsAwareInterface
{
    use EventsAwareTrait;
    
    /**
     * Gets value from getter
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }
        
        throw new EntityException(
            sprintf('No getter for %s::$%s is defined', static::class, $name)
        );
    }
    
    /**
     * Checks if getter exists
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name): bool
    {
        return method_exists($this, 'get' . ucfirst($name));
    }
    
    /**
     * Exports snapshot based on it's properties
     *
     * @param string $snapshotClassName
     *
     * @return mixed
     */
    protected function exportSnapshot(string $snapshotClassName): mixed
    {
        $r = new ReflectionClass($snapshotClassName);
        $snapshotArr = [];
        foreach ($r->getProperties() as $property) {
            if (!$property->getType()) {
                continue;
            }
            
            $name = $property->getName();
            
            // snapshot property name matches entity's one
            if (property_exists($this, $name)) {
                $value = $this->{$name};
                if ($value instanceof EntityInterface) {
                    $value = $value->toSnapshot();
                }
                $snapshotArr[$name] = $value;
                continue;
            }
            
            /* @noinspection GrazieInspection */
            // snapshot property name is entity name with suffix "Id"
            $entityName = rtrim($name, 'Id');
            $entity = $this->{$entityName} ?? null;
            if (property_exists($this, $entityName)) {
                if ($entity instanceof IdentifiableEntityInterface || is_null($entity)) {
                    $snapshotArr[$name] = $entity ? $entity->getId() : $entity;
                }
                continue;
            }
            
            throw new EntityException(
                sprintf('Unable to export an unknown entity property "%s"', $name)
            );
        }
        
        return call_user_func([$snapshotClassName, 'fromArray'], $snapshotArr);
    }
    
    /**
     * Imports entity snapshot
     *
     * @param SnapshotInterface $snapshot
     * @param array             $resolved
     * @param array             $ignoredSnapshotFields
     */
    protected function importSnapshot(
        SnapshotInterface $snapshot,
        array $resolved = [],
        array $ignoredSnapshotFields = []
    ): void {
        $r = new ReflectionClass($this);
        
        if (!empty($ignoredSnapshotFields)) {
            $ignoredSnapshotFields = array_flip($ignoredSnapshotFields);
        }
        
        foreach ($snapshot as $name => $value) {
            if (
                isset($ignoredSnapshotFields[$name])
                && array_key_exists($name, $ignoredSnapshotFields)
            ) {
                continue;
            }
            
            if ($this->setValue($r, $name, $value)) {
                continue;
            }
            
            if ($this->setValue($r, rtrim($name, 'Id'), $value, $resolved)) {
                continue;
            }
            
            throw new EntityException(
                sprintf('Unable to import from unknown snapshot property "%s"', $name)
            );
        }
    }
    
    /**
     * Sets property value based on its type
     *
     * @param ReflectionClass $self
     * @param string          $propName
     * @param                 $value
     * @param array           $resolved
     *
     * @return bool
     */
    private function setValue(
        ReflectionClass $self,
        string $propName,
        $value,
        array $resolved = []
    ): bool {
        $success = false;
        
        if ($self->hasProperty($propName) && ($prop = $self->getProperty($propName))) {
            
            $prop->setAccessible(true);
            $isInitialized = $prop->isInitialized($this);
            $type = $prop->getType();
            $typeClass = $type instanceof ReflectionNamedType
                ? $type->getName()
                : null;
            
            switch (true) {
                // optional and empty or already initialized with non-empty value
                case($value === null && $type->allowsNull()):
                case($isInitialized && $prop->getValue($this) !== null):
                    $success = true;
                    break;
                
                // property type and value are of the same type
                case($typeClass && $value instanceof $typeClass):
                    $this->{$propName} = $value;
                    $success = true;
                    break;
                
                // has resolved value
                case(array_key_exists($propName, $resolved)):
                    $this->{$propName} = $resolved[$propName];
                    $success = true;
                    break;
            }
        }
        
        return $success;
    }
}
