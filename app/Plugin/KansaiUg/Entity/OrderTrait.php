<?php


namespace Plugin\KansaiUg\Entity;


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
    private $kansai_ug_noshi = false;

    /**
     * @return bool
     */
    public function isKansaiUgNoshi(): ?bool
    {
        return $this->kansai_ug_noshi;
    }

    /**
     * @param bool $kansai_ug_noshi
     */
    public function setKansaiUgNoshi(bool $kansai_ug_noshi): void
    {
        $this->kansai_ug_noshi = $kansai_ug_noshi;
    }
}
