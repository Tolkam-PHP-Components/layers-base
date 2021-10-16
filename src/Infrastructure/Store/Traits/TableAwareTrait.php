<?php

namespace Tolkam\Layers\Base\Infrastructure\Store\Traits;

use InvalidArgumentException;

trait TableAwareTrait
{
    /**
     * @var string|null
     */
    private ?string $table = null;
    
    /**
     * @var array
     */
    private array $tables = [];
    
    /**
     * Sets table name
     *
     * @param string      $value
     * @param string|null $alias
     *
     * @return static
     */
    public function setTable(string $value, string $alias = null): static
    {
        if (!empty($alias)) {
            $this->tables[$alias] = $value;
        }
        else {
            $this->table = $value;
        }
        
        return $this;
    }
    
    /**
     * Gets table name
     *
     * @param string|null $alias
     *
     * @return string
     */
    public function getTable(string $alias = null): string
    {
        if (!empty($alias)) {
            $table = $this->tables[$alias] ?? null;
        }
        else {
            $table = $this->table;
        }
        
        if (!$table) {
            throw new InvalidArgumentException(sprintf(
                'No %s table is set for %s',
                $alias ? '"' . $alias . '"' : 'default',
                static::class
            ));
        }
        
        return $table;
    }
    
    /**
     * Gets all registered tables
     * as [alias => table] array
     *
     * @return array
     */
    public function getTables(): array
    {
        $tables = $this->tables;
        
        if ($this->table) {
            $tables[''] = $this->table;
        }
        
        return $tables;
    }
}
