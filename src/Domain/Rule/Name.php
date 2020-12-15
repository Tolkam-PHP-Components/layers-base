<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Rule;

use Tolkam\Rules\FailureInterface;
use Tolkam\Rules\Rule;

class Name extends Rule
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 100;
    
    /**
     * @inheritDoc
     */
    public function apply($value)
    {
        $sequence = new Rule\Sequence();
        
        // length
        $sequence->add(new Rule\Length([
                'min' => self::MIN_LENGTH,
                'max' => self::MAX_LENGTH,
            ]
        ));
        
        // allowed characters
        $sequence->add(new Rule\Callback(function ($value) {
            return $this->checkCharacters($value);
        }));
        
        return $sequence->apply($value);
    }
    
    /**
     * @param $value
     *
     * @return FailureInterface|null
     */
    protected function checkCharacters($value)
    {
        $code = 'invalid.characters';
        $text = 'Name contains illegal characters';
        
        // allow cyrillic, latin, extra (dash, apostrophe, dot and space), starting with letter
        if (!preg_match('~^[\p{Cyrillic}\p{Latin}][\p{Cyrillic}\p{Latin}\-\'.\040]*$~ui', $value)) {
            $code .= '.letters';
            
            return $this->failure($code, $text);
        }
        
        // allow ending with letter, apostrophe or dot only
        if (!preg_match('~[\p{Cyrillic}\p{Latin}\'.]$~ui', $value)) {
            $code .= '.ending';
            
            return $this->failure($code, $text);
        }
        
        // do not allow repeating extra chars
        if (preg_match('~(-{2}|\'{2}|\s{2}|\.{2})~ui', $value)) {
            $code .= '.repeating';
            
            return $this->failure($code, $text);
        }
        
        // limit each extra char total count
        if (max(
                mb_substr_count($value, ' '),
                mb_substr_count($value, '\''),
                mb_substr_count($value, '-'),
                mb_substr_count($value, '.')
            ) > 2) {
            $code .= '.extrasCount';
            
            return $this->failure($code, $text);
        }
        
        return null;
    }
}
