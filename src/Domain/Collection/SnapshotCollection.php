<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Collection;

abstract class SnapshotCollection extends Collection
{
    /**
     * Collects items property values
     *
     * @param array         $propsToCollect
     * @param callable|null $castCallable
     *
     * @return array
     */
    public function collectValues(array $propsToCollect, callable $castCallable = null): array
    {
        // cast to string by default
        $castCallable ??= 'strval';
        $collected = [];
        
        $this->each(static function ($item) use (&$collected, $propsToCollect, $castCallable) {
            foreach ($propsToCollect as $prop) {
                if (($value = $item->{$prop}) && $value !== null) {
                    /** @noinspection PhpArrayUsedOnlyForWriteInspection */
                    $collected[$prop][] = $castCallable && is_callable($castCallable)
                        ? call_user_func($castCallable, $value->value())
                        : $value;
                }
            }
        });
        
        // keep unique values or use placeholder if no values found
        foreach ($propsToCollect as $prop) {
            $collected[$prop] = isset($collected[$prop]) ? array_unique($collected[$prop]) : [];
        }
        
        return $collected;
    }
}
