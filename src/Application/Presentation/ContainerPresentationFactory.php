<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Application\Presentation;

use Psr\Container\ContainerInterface;

class ContainerPresentationFactory implements PresentationFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @inheritDoc
     */
    public function make(string $id): callable
    {
        return $this->container->get($id);
    }
}
