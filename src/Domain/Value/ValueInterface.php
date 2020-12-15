<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value;

interface ValueInterface
{
    /**
     * Returns string representation of value
     *
     * @return string
     */
    public function __toString(): string;
    
    /**
     * Restores value from string representation
     *
     * @param string $str
     *
     * @return mixed
     */
    public static function fromString(string $str);
    
    /**
     * Gets original value
     *
     * @return mixed
     */
    public function value();
    
    /**
     * Checks if value equals to other one
     *
     * @param $otherValue
     *
     * @return bool
     */
    public function equals($otherValue): bool;
}
