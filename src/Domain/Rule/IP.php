<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Rule;

use Tolkam\Rules\Rule;

class IP extends Rule
{
    /**
     * @var int
     */
    protected int $flags;
    
    /**
     * @param int $flags
     */
    public function __construct(int $flags = 0)
    {
        $this->flags = $flags;
    }
    
    /**
     * @inheritDoc
     */
    public function apply($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP, $this->flags)) {
            return $this->failure('invalid', 'Invalid IP address');
        }
        
        return null;
    }
}
