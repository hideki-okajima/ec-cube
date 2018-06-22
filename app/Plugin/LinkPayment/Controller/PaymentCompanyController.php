<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 14:09
 */

namespace Plugin\LinkPayment\Controller;


use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PaymentCompanyController extends AbstractController
{
    /**
     * @Route("/payment_company", name="payment_company")
     * @Template("LinkPayment/Resource/index.twig")
     */
    public function index(Request $request)
    {
        $orderCode = $request->get('code');

        if ('POST' === $request->getMethod()) {
            // EC-CUBEの購入完了画面へのリンク
            $url = '/sample_payment_complete';

            // 注文番号を付与
            $url .= '?code=' . $orderCode;

            // TODO POSTにしたい
            return new RedirectResponse($url);
        }

        return [
            'order_code' => $orderCode,
        ];
    }
}