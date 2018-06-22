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
use Symfony\Component\HttpFoundation\Request;

class PaymentCompanyController extends AbstractController
{
    /**
     * @Route("/payment_company", name="payment_company")
     * @Template("LinkPayment/Resource/index.twig")
     */
    public function index(Request $request)
    {
        $OrderCode = $request->get('code');

        return [
            'order_code' => $OrderCode,
        ];
    }
}