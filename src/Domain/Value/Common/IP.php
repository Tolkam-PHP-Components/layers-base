<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value\Common;

use Tolkam\Base\Domain\Rule\IP as IPRule;
use Tolkam\Base\Domain\Value\RulesTrait;

class IP extends StringValue
{
    use RulesTrait;
    
    /**
     * @inheritDoc
     */
    public function __construct(string $value)
    {
        self::applyRule(new IPRule, $value);
        
        parent::__construct($value);
    }
}
