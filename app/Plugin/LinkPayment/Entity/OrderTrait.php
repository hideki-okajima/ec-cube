<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 18:23
 */

namespace Plugin\LinkPayment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * トークンを保持するカラム.
     *
     * dtb_order.link_payment_token
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     *
     * TODO 命名規約.いったんプラグインコードのスネークケースで.
     * TODO 文字長はどれくらいが適切？
     */
    private $link_payment_token;

    // TODO カラム名、変数名が不適切
    /**
     * 決済ステータスを保持するカラム.
     *
     * dtb_order.link_payment_payment_status_id
     *
     * @var PaymentStatus
     * @ORM\ManyToOne(targetEntity="Plugin\LinkPayment\Entity\PaymentStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="link_payment_payment_id", referencedColumnName="id")
     * })
     */
    private $LinkPaymentPaymentStatus;

    /**
     * @return string
     */
    public function getLinkPaymentToken()
    {
        return $this->link_payment_token;
    }

    /**
     * @param string $link_payment_token
     *
     * @return $this
     */
    public function setLinkPaymentToken($link_payment_token)
    {
        $this->link_payment_token = $link_payment_token;

        return $this;
    }

    /**
     * @return PaymentStatus
     */
    public function getLinkPaymentPaymentStatus()
    {
        return $this->LinkPaymentPaymentStatus;
    }

    /**
     * @param PaymentStatus $LinkPaymentPaymentStatus|null
     */
    public function setLinkPaymentPaymentStatus(PaymentStatus $LinkPaymentPaymentStatus = null)
    {
        $this->LinkPaymentPaymentStatus = $LinkPaymentPaymentStatus;
    }
}
