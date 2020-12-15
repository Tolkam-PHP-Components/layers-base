<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Transaction;

interface TransactionStackInterface
{
    /**
     * Processes the stack
     *
     * @param $initialValue
     *
     * @return mixed
     */
    public function process($initialValue);
}
