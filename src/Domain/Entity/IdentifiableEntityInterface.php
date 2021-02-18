<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

use Tolkam\Layers\Base\Domain\Value\ValueInterface;

interface IdentifiableEntityInterface extends EntityInterface
{
    /**
     * @return string
     */
    public static function type(): string;
    
    /**
     * Gets entity id
     *
     * @return ValueInterface
     */
    public function getId(): ValueInterface;
}
