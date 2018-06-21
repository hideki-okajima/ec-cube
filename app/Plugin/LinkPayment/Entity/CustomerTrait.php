<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 18:22
 */

namespace Plugin\LinkPayment\Entity;

use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * カードの記憶用カラム.
     *
     * @var string
     * @ORM\Column(type="smallint", nullable=true)
     *
     * TODO 命名規約が必要.
     */
    public $sample_token_cards;
}
