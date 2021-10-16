<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

use Tolkam\Layers\Base\Domain\Value\EqualityTrait;
use Tolkam\Layers\Base\Domain\Value\RulesTrait;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;
use Tolkam\Rules\Rule\Email as EmailRule;
use Tolkam\Utils\Str;

class Email implements ValueInterface
{
    use RulesTrait, EqualityTrait;
    
    /**
     * @var string
     */
    private string $value;
    
    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        self::applyRule(new EmailRule, $value);
        
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public static function fromString(string $str): static
    {
        return new static($str);
    }
    
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value();
    }
    
    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return $this->value;
    }
    
    /**
     * Gets mailbox part
     *
     * @return string
     */
    public function getMailbox(): string
    {
        return mb_substr($this->value, 0, mb_strpos($this->value, '@'));
    }
    
    /**
     * Gets domain part
     *
     * @return string
     */
    public function getDomain(): string
    {
        return mb_substr($this->value, mb_strpos($this->value, '@') + 1);
    }
    
    /**
     * Masks the mailbox
     *
     * @param string $maskChar
     *
     * @return string
     */
    public function getMasked(string $maskChar = '*'): string
    {
        $mailbox = Str::mask($this->getMailbox(), 25, 2, $maskChar);
        
        return implode('@', [$mailbox, $this->getDomain()]);
    }
}
