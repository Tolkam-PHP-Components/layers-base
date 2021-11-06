<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Rule;

use DateTimeImmutable;
use Throwable;
use Tolkam\Rules\FailureInterface;
use Tolkam\Rules\Rule;

class Time extends Rule
{
    /**
     * @inheritDoc
     */
    public function apply($value): ?FailureInterface
    {
        $failure = $this->failure('invalid', 'Invalid time value');
        
        if ($value === '') {
            return $failure;
        }
        
        // if it looks like timestamp, make it parseable
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $value = '@' . $value;
        }
        
        try {
            new DateTimeImmutable($value);
        } catch (Throwable $t) {
            return $failure;
        }
        
        return null;
    }
}
