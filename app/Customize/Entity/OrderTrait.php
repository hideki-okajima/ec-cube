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
     * @var bool|null
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $noshi = false;

    /**
     * @return bool|null
     */
    public function getNoshi(): ?bool
    {
        return $this->noshi;
    }

    /**
     * @param bool|null $noshi
     */
    public function setNoshi(?bool $noshi): void
    {
        $this->noshi = $noshi;
    }
}
