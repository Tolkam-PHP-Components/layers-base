<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Infrastructure\Store;

use InvalidArgumentException;

class FilterHandlerRegistry
{
    /**
     * @var callable[]
     */
    private array $handlers = [];
    
    /**
     * @param array|null $handlers
     */
    public function __construct(array $handlers = null)
    {
        if ($handlers) {
            $this->registerHandlers($handlers);
        }
    }
    
    /**
     * @param string   $filterAlias
     * @param callable $handler
     *
     * @return self
     */
    public function registerHandler(string $filterAlias, callable $handler): self
    {
        $this->handlers[$filterAlias] = $handler;
        
        return $this;
    }
    
    /**
     * @param array $handlers
     *
     * @return self
     */
    public function registerHandlers(array $handlers): self
    {
        foreach ($handlers as $filterAlias => $handler) {
            $this->registerHandler($filterAlias, $handler);
        }
        
        return $this;
    }
    
    /**
     * @param string $filterAlias
     *
     * @return callable
     */
    public function getHandler(string $filterAlias): callable
    {
        if (!isset($this->handlers[$filterAlias])) {
            throw new InvalidArgumentException(sprintf(
                'Filter handler for "%s" is not registered',
                $filterAlias
            ));
        }
        
        return $this->handlers[$filterAlias];
    }
    
    /**
     * @return array|callable[]
     * @noinspection PhpDocSignatureInspection
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
