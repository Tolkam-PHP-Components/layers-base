<?php declare(strict_types=1);

namespace Tolkam\Base\Common\Exception;

use Exception;
use InvalidArgumentException;
use Throwable;

class DetailedErrorException extends Exception
{
    /**
     * @var string
     */
    protected string $errorCode;
    
    /**
     * @var string
     */
    protected string $errorText;
    
    /**
     * @var array
     */
    protected array $errorParams;
    
    /**
     * @var array|null
     */
    private ?array $extraMessages = null;
    
    /**
     * @param string         $errorCode
     * @param string         $errorText
     * @param array          $errorParams
     * @param Throwable|null $previous
     */
    public function __construct(
        string $errorCode,
        string $errorText = '',
        array $errorParams = [],
        Throwable $previous = null
    ) {
        $allowed = 'a-zA-Z\.';
        if (preg_match('~[^' . $allowed . ']~', $errorCode)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid error code: allowed characters are "%s"',
                stripslashes($allowed)
            ));
        }
        
        $this->errorCode = $errorCode;
        $this->errorText = $errorText;
        $this->errorParams = $errorParams;
        
        parent::__construct($errorText, 0, $previous);
    }
    
    /**
     * Gets the error code
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
    
    /**
     * Gets the error text
     *
     * @return string
     */
    public function getErrorText(): string
    {
        return $this->errorText;
    }
    
    /**
     * Gets the error parameters
     *
     * @return array
     */
    public function getErrorParams(): array
    {
        return $this->errorParams;
    }
    
    /**
     * Adds extra message
     *
     * @param string      $name
     * @param string      $code
     * @param string|null $text
     * @param array       $params
     *
     * @return self
     */
    public function addExtraMessage(
        string $name,
        string $code,
        string $text = null,
        array $params = []
    ): self {
        $code = 'named' . '.' . $name . '.' . $code;
        $this->extraMessages[$name] = compact('code', 'text', 'params');
        
        return $this;
    }
    
    /**
     * Gets the extra messages
     *
     * @return array|null
     */
    public function getExtraMessages(): ?array
    {
        return $this->extraMessages;
    }
}
