<?php

namespace Tolkam\Layers\Base\Application\Rule;

use InvalidArgumentException;
use Tolkam\Layers\Base\Application\Exception\ApplicationException;
use Tolkam\Rules\Rule\Arr;
use Tolkam\Rules\RuleInterface;

trait RulesTrait
{
    /**
     * Applies rules to the value
     *
     * @param       $value
     * @param array $rules
     *
     */
    protected function applyRules($value, array $rules)
    {
        foreach ($rules as $rule) {
            if (!$rule instanceof RuleInterface) {
                throw new InvalidArgumentException(sprintf(
                    'Each $rules value must be an instance of %s, %s given',
                    RuleInterface::class,
                    gettype($rule)
                ));
            }
        }
        
        if ($failures = (new Arr($rules))->apply($value)) {
            $exception = new ApplicationException('input.invalid');
            
            foreach ($failures->flatten()->toArray() as $name => $failure) {
                $exception->addExtraMessage(
                    $name,
                    $failure['code'],
                    $failure['text'],
                    $failure['params']
                );
            }
            
            throw $exception;
        }
    }
}
