<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/03/22
 * Time: 5:41
 */

namespace Acme\Entity;

use Eccube\Annotation as Eccube;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(message="入力してくださいね！！！")
     * @Eccube\FormAppend(
     *     auto_render=true,
     *     form_theme="Form/hobby.twig",
     *     )
     */
    public $hobby;
}
