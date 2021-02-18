<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use Tolkam\Layers\Base\Domain\Rule\IP as IPRule;
use Tolkam\Layers\Base\Domain\Value\RulesTrait;

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
