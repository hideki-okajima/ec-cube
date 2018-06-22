<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 13:48
 */

namespace Plugin\LinkPayment\Service;


use Eccube\Service\Payment\PaymentMethod;

class CreditCard implements PaymentMethod
{

    public function checkout()
    {
        // リンク型は使用しない
    }

    // TODO Interfaceに追加と呼び出し元の処理が必要
    public function verify()
    {
        // リンク型は使用しない
    }

    public function apply()
    {
        // ここでは決済方法の独自処理を記載する

        // TODO 以下の処理を追加
        // 決済の独自処理
        // 注文番号を送信する

        return new RedirectResponse('/payment_company');
    }

    public function setFormType($form)
    {
        // TODO: Implement setFormType() method.
    }

    public function setRequest($request)
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