<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 13:52
 */

namespace Plugin\LinkPayment\Service;


use Eccube\Service\Payment\PaymentMethod;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * 決済会社の画面へリダイレクト
     *
     * @param PaymentMethod $PaymentMethod
     * @return RedirectResponse
     */
    public function dispatch(PaymentMethod $PaymentMethod)
    {
        // TODO 以下の更新処理の追加
        // - 受注ステータスの変更（購入処理中 -> 決済処理中）
        // - 決済ステータス（なし -> 未決済）
        // - 在庫を減らす
        // - ポイントを減らす
        // 注文番号を送信する
        return new RedirectResponse('/payment_company');
    }
}