<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Transaction;

use SplStack;

class TransactionStack implements TransactionStackInterface
{
    /**
     * @var SplStack
     */
    private SplStack $stack;
    
    /**
     * @param mixed ...$callables
     */
    public function __construct(...$callables)
    {
        $this->stack = new SplStack;
        
        foreach ($callables as $callable) {
            is_callable($callable) && $this->push($callable);
        }
    }
    
    /**
     * @param callable $callable
     *
     * @return self
     */
    public function push(callable $callable): self
    {
        $this->stack->push($callable);
        
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function process($initialValue)
    {
        if (!$this->stack->isEmpty()) {
            $value = call_user_func($this->stack->pop(), $this->process($initialValue));
            
            return isset($value) ? $value : $initialValue;
        }
        
        return $initialValue;
    }
}
