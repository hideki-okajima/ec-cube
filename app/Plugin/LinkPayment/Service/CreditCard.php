<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 13:48
 */

namespace Plugin\LinkPayment\Service;


use Eccube\Entity\Order;
use Eccube\Service\Payment\PaymentMethod;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CreditCard implements PaymentMethod
{
    /**
     * @var Order
     */
    private $Order;

    public function checkout()
    {
        // リンク型は使用しない
    }

    // TODO Interfaceに追加と呼び出し元の処理が必要
    public function verify()
    {
        // リンク型は使用しない
    }

    /**
     * ここでは決済方法の独自処理を記載する
     *
     * 決済会社の画面へリダイレクト
     *
     * @return RedirectResponse
     */
    public function apply()
    {
        // 決済の独自処理

        // 決済会社の決済画面へのリンク
        $url = '/payment_company';

        // 注文番号を付与
        $orderCode = $this->Order->getOrderCode();
        $url .= '?code=' . $orderCode;

        return new RedirectResponse($url);
    }

    /**
     * @param FormTypeInterface
     *
     * TODO FormTypeInterface -> FormInterface
     */
    public function setFormType(FormInterface $form)
    {
        $this->Order = $form->getData();

        // TODO Orderエンティティにトークンが保持されているのでフォームは不要
        // TODO フォームよりOrderがほしい
        // TODO applyやcheckoutでOrderが渡ってきてほしい.
        // TODO やっぱりFormはいる -> Orderには保持しないデータはFormで引き回す(確認画面とか). 画面に持っていくデータを詰められるオブジェクトがあればいいのかな

    }

    public function setRequest(Request $request)
    {

    }

    // TODO 消す
    public function setApplication($app)
    {
        //
    }

    // TODO Interfaceに追加と呼び出し元の処理が必要
    public function receive()
    {

    }
}