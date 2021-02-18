<?php

namespace Tolkam\Layers\Base\Domain\Value;

use Tolkam\Rules\Failure;
use Tolkam\Rules\Failures;
use Tolkam\Rules\RuleInterface;

trait RulesTrait
{
    /**
     * Validates value against rule
     *
     * @param RuleInterface $rule
     * @param               $value
     */
    protected static function applyRule(RuleInterface $rule, $value): void
    {
        $failures = $rule->apply($value);
        
        $failures = $failures instanceof Failure
            ? new Failures([$failures])
            : $failures;
        
        if (!empty($failures)) {
            $failures = $failures->flatten()->toArray();
            $key = key($failures);
            $text = $failures[$key]['text'];
            
            $message = sprintf('Failed to validate "%s" (', static::class);
            if (is_string($key)) {
                $message .= '[' . $key . ']: ';
            }
            $message .= $text . ')';
            
            throw new ValueException($message);
        }
    }
}
