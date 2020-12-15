<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Entity;

use Tolkam\Base\Domain\Value\ValueInterface;

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
