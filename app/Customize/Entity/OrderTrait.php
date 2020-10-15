<?php


namespace Customize\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Annotation\FormAppend;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     * @FormAppend(
     *     auto_render=true,
     *     type="Symfony\Component\Form\Extension\Core\Type\ChoiceType",
     *     options={"choices": {"あり": true, "なし": false}, "label": "熨斗"}
     * )
     */
    private $noshi = false;

    /**
     * @return bool
     */
    public function isNoshi()
    {
        return $this->noshi;
    }

    /**
     * @param bool $noshi
     */
    public function setNoshi($noshi)
    {
        $this->noshi = $noshi;
    }
}
