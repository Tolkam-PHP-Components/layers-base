<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Application\Presentation;

use Tolkam\Layers\Base\Domain\Collection\EntityCollection;
use Tolkam\Layers\Base\Domain\Entity\Entity;

class PresentationDefinition
{
    /**
     * @param Entity|EntityCollection $source
     * @param string                  $presentationId
     */
    public function __construct(
        protected Entity|EntityCollection $source,
        protected string $presentationId
    ) {
    }
    
    /**
     * Gets the source
     *
     * @return EntityCollection|Entity
     */
    public function getSource(): Entity|EntityCollection
    {
        return $this->source;
    }
    
    /**
     * Gets the presentation id
     *
     * @return string
     */
    public function getPresentationId(): string
    {
        return $this->presentationId;
    }
}
