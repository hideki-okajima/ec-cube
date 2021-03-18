<?php


namespace Plugin\KansaiUg\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Annotation\FormAppend;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="入力してください")
     * @FormAppend(
     *     auto_render=true,
     *     type="\Symfony\Component\Form\Extension\Core\Type\TextType",
     *     options={"required": true, "label": "趣味"}
     * )
     */
    private $kansai_ug_hobby;

    /**
     * @return string|null
     */
    public function getKansaiUgHobby(): ?string
    {
        return $this->kansai_ug_hobby;
    }

    /**
     * @param string|null $kansai_ug_hobby
     */
    public function setKansaiUgHobby(?string $kansai_ug_hobby): void
    {
        $this->kansai_ug_hobby = $kansai_ug_hobby;
    }
}
