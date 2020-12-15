<?php declare(strict_types=1);

namespace Tolkam\Base\Application\Presentation;

use Tolkam\Base\Domain\Collection\EntityCollection;
use Tolkam\Base\Domain\Entity\EntityInterface;

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
