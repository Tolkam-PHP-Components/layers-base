<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Application\Presentation\Renderer;

use RuntimeException;
use Tolkam\Layers\Base\Application\Presentation\PresentationDefinition;
use Tolkam\Layers\Base\Application\Presentation\PresentationFactoryInterface;
use Tolkam\Layers\Base\Application\Presentation\PresentationRendererInterface;
use Tolkam\Layers\Base\Domain\Collection\EntityCollection;
use Tolkam\Layers\Base\Domain\Entity\EntityInterface;

class DeepPresentationRenderer implements PresentationRendererInterface
{
    /**
     * @param PresentationFactoryInterface $factory
     */
    public function __construct(
        protected PresentationFactoryInterface $factory
    ) {
    }
    
    /**
     * @param EntityInterface|EntityCollection $source
     * @param string                           $presentationId
     *
     * @return array
     */
    public function render(
        EntityInterface|EntityCollection $source,
        string $presentationId
    ): array {
        $presentation = $this->factory->make($presentationId);
        
        if ($source instanceof EntityCollection) {
            $arr = $source->toArray(fn($v) => $this->render($v, $presentationId));
        }
        else {
            $arr = $presentation($source);
        }
        
        if (!is_array($arr)) {
            throw new RuntimeException(sprintf(
                'Presentation must return an array, %s returned',
                is_object($arr) ? get_class($arr) : gettype($arr)
            ));
        }
        
        foreach ($arr as $k => $v) {
            if ($v instanceof PresentationDefinition) {
                $v = $this->render($v->getSource(), $v->getPresentationId());
            }
            $arr[$k] = $v;
        }
        
        return $arr;
    }
}
