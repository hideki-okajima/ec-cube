<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/03/22
 * Time: 9:34
 */

namespace Acme\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Annotation\FormAppend;


/**
 * Trait CustomerTrait
 * @package Acme\Entity
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @FormAppend(
     *     auto_render=true,
     *     form_theme="Form/hobby.twig"
     * )
     */
    public $hobby;
}
