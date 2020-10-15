<?php


namespace Customize\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noshi = false;

    /**
     * @return bool
     */
    public function isNoshi(): bool
    {
        return $this->noshi;
    }

    /**
     * @param bool $noshi
     */
    public function setNoshi(bool $noshi): void
    {
        $this->noshi = $noshi;
    }
}
