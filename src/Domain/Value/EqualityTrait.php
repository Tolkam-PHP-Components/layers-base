<?php

namespace Tolkam\Layers\Base\Domain\Value;

trait EqualityTrait
{
    /**
     * @param $otherValue
     *
     * @return bool
     */
    public function equals($otherValue): bool
    {
        return $otherValue !== null
            && get_class($otherValue) === get_class($this)
            && ((string) $otherValue === (string) $this);
    }
}
