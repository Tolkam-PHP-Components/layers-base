<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Repository\Filter;

use Tolkam\Base\Domain\Repository\Filter;

abstract class BooleanFilter extends Filter
{
    /**
     * @var bool
     */
    protected bool $value;
    
    /**
     * @param bool $value
     */
    public function __construct(bool $value = true)
    {
        $this->value = $value;
    }
    
    /**
     * Gets the value
     *
     * @return bool
     */
    public function getValue(): bool
    {
        return $this->value;
    }
}
