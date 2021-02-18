<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Application\Presentation;

use Tolkam\Layers\Base\Domain\Collection\EntityCollection;
use Tolkam\Layers\Base\Domain\Entity\EntityInterface;

interface PresentationRendererInterface
{
    /**
     * @param EntityInterface|EntityCollection $source
     * @param string                           $presentationId
     *
     * @return array
     */
    public function render(
        EntityInterface|EntityCollection $source,
        string $presentationId
    ): array;
}
