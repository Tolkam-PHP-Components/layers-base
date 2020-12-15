<?php

namespace Tolkam\Base\Application\Common\Traits;

trait MaxResultsAwareTrait
{
    /**
     * @var int|null
     */
    private ?int $maxResults = null;
    
    /**
     * @param int $value
     */
    public function setMaxResults(int $value)
    {
        $this->maxResults = $value;
    }
    
    /**
     * Gets the max results
     *
     * @return int|null
     */
    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }
}
