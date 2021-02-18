<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Application\Presentation;

use Tolkam\Layers\Base\Application\Exception\ApplicationException;
use Tolkam\Layers\Base\Domain\Collection\EntityCollection;
use Tolkam\Layers\Base\Domain\Entity\EntityInterface;
use Tolkam\Layers\Base\Domain\Value\ValueInterface;

class PresentationRenderer implements PresentationRendererInterface
{
    /**
     * @param PresentationFactoryInterface $factory
     */
    public function __construct(
        protected PresentationFactoryInterface $factory
    ) {
    }
    
    /**
     * @inheritDoc
     */
    public function render(
        EntityInterface|EntityCollection $source,
        string $presentationId
    ): array {
        $presentation = $this->factory->make($presentationId);
        
        if ($source instanceof EntityCollection) {
            $arr = $source->toArray($presentation);
        }
        else {
            $arr = $presentation($source);
        }
        
        if (!is_array($arr)) {
            throw new ApplicationException(sprintf(
                'Presentation must return an array, %s returned',
                is_object($arr) ? get_class($arr) : gettype($arr)
            ));
        }
        
        foreach ($arr as $k => $v) {
            if ($v instanceof PresentationDefinition) {
                $v = $this->render($v->getSource(), $v->getPresentationId());
            }
            elseif ($v instanceof ValueInterface) {
                $v = $v->value();
            }
            $arr[$k] = $v;
        }
        
        return $arr;
    }
}
