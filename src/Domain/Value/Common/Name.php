<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value\Common;

use Tolkam\Base\Domain\Rule;
use Tolkam\Base\Domain\Value\EqualityTrait;
use Tolkam\Base\Domain\Value\RulesTrait;
use Tolkam\Base\Domain\Value\ValueInterface;

class Name implements ValueInterface
{
    use EqualityTrait, RulesTrait;
    
    /**
     * @var string
     */
    private string $value;
    
    /**
     * @inheritDoc
     */
    public function __construct(string $value)
    {
        // reusable rule instead of in-place validation
        self::applyRule(new Rule\Name, $value);
        
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str)
    {
        return new static($str);
    }
    
    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return $this->value;
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
