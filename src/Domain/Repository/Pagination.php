<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Repository;

use InvalidArgumentException;

/**
 * @property int             $strategy
 * @property string          $primarySortProp
 * @property string|null     $backupSortProp
 * @property string|int|null $previousCursor
 * @property string|int|null $currentCursor
 * @property string|int|null $nextCursor
 * @property bool            $reverseResults
 * @property int             $maxResults
 * @property string          $primaryOrder
 * @property string|null     $backupOrder
 */
class Pagination
{
    const STRATEGY_NONE   = 10;
    const STRATEGY_OFFSET = 11;
    const STRATEGY_CURSOR = 12;
    
    const KNOWN_STRATEGIES = [
        self::STRATEGY_NONE,
        self::STRATEGY_OFFSET,
        self::STRATEGY_CURSOR,
    ];
    
    const ORDER_ASC  = 'ASC';
    const ORDER_DESC = 'DESC';
    
    /**
     * @var int
     */
    protected int $strategy = self::STRATEGY_OFFSET;
    
    /**
     * @var string
     */
    protected string $primarySortProp = '';
    
    /**
     * @var string|null
     */
    protected ?string $backupSortProp = null;
    
    /**
     * @var string|int|null
     */
    protected string|int|null $previousCursor = null;
    
    /**
     * @var string|int|null
     */
    protected string|int|null $currentCursor = null;
    
    /**
     * @var string|int|null
     */
    protected string|int|null $nextCursor = null;
    
    /**
     * @var bool
     */
    protected bool $reverseResults = false;
    
    /**
     * @var int
     */
    protected int $maxResults = 100;
    
    /**
     * @var string
     */
    protected string $primaryOrder = self::ORDER_DESC;
    
    /**
     * @var string|null
     */
    protected ?string $backupOrder = null;
    
    /**
     * @return static
     */
    public static function make(): self
    {
        return new static;
    }
    
    private function __construct()
    {
    }
    
    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new PaginationException(
                sprintf('Unknown criteria "%s"', $name)
            );
        }
        
        return $this->{$name};
    }
    
    /**
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            call_user_func([$this, $methodName], $value);
        }
        else {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
            else {
                throw new PaginationException(
                    sprintf('Unable to set an unknown property "%s"', $name)
                );
            }
        }
    }
    
    /**
     * Sets the strategy type
     *
     * @param int $strategy
     */
    protected function setStrategy(int $strategy)
    {
        if (!in_array($strategy, self::KNOWN_STRATEGIES)) {
            throw new InvalidArgumentException(sprintf(
                'Unknown pagination strategy "%s"',
                $strategy
            ));
        }
        
        $this->strategy = $strategy;
    }
    
    /**
     * @return bool
     */
    public function isCursorPagination(): bool
    {
        return $this->strategy === self::STRATEGY_CURSOR;
    }
    
    /**
     * @return bool
     */
    public function isOffsetPagination(): bool
    {
        return $this->strategy === self::STRATEGY_OFFSET;
    }
    
    /**
     * Sets current cursor value
     *
     * @param $cursor
     */
    protected function setCurrentCursor($cursor)
    {
        $this->updateCursor($cursor, 'current');
    }
    
    /**
     * Sets previous cursor value
     *
     * @param $cursor
     */
    protected function setPreviousCursor($cursor)
    {
        $this->updateCursor($cursor, 'previous');
    }
    
    /**
     * Sets next cursor value
     *
     * @param $cursor
     */
    protected function setNextCursor($cursor)
    {
        $this->updateCursor($cursor, 'next');
    }
    
    /**
     * Sets the pagination strategy
     *
     * @param int|null $maxResults
     */
    protected function setMaxResults(?int $maxResults)
    {
        if ($maxResults === null) {
            return;
        }
        
        if ($maxResults < 1) {
            throw new InvalidArgumentException('Max results value must be positive integer');
        }
        
        $this->maxResults = $maxResults;
    }
    
    /**
     * Sets the order for primary sort prop
     *
     * @param string $primaryOrder
     */
    protected function setPrimaryOrder(string $primaryOrder)
    {
        $this->validateOrder($primaryOrder);
        
        $this->primaryOrder = $primaryOrder;
    }
    
    /**
     * Sets the order for backup sort prop
     *
     * @param string $order
     */
    protected function setBackupOrder(string $order)
    {
        $this->validateOrder($order);
        
        $this->backupOrder = $order;
    }
    
    /**
     * Updates cursor at position value
     *
     * @param        $cursor
     * @param string $position
     */
    private function updateCursor($cursor, string $position)
    {
        $isInt = is_int($cursor);
        $isString = is_string($cursor);
        
        if (!is_null($cursor) && !$isString && !$isInt) {
            throw new InvalidArgumentException(sprintf(
                '%s cursor may be string, integer or null, %s given',
                ucfirst($position),
                gettype($cursor)
            ));
        }
        
        if ($isInt && $cursor < 1) {
            throw new InvalidArgumentException(sprintf(
                '%s cursor must be greater than 0',
                ucfirst($position),
            ));
        }
        
        $this->{$position . 'Cursor'} = $cursor ?: null;
    }
    
    /**
     * @param string $order
     */
    private function validateOrder(string $order): void
    {
        if (!in_array($order, [self::ORDER_ASC, self::ORDER_DESC])) {
            throw new InvalidArgumentException(sprintf('Unknown sort order "%s"', $order));
        }
    }
}
