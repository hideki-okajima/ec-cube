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
use Eccube\Service\PaymentService as BasePaymentService;

class PaymentService extends BasePaymentService
{
    /**
     * ここでは決済会社の共通処理を記載する
     *
     * @param PaymentMethod $method
     * @return RedirectResponse
     */
    public function dispatch(PaymentMethod $method)
    {
        // TODO 以下の更新処理の追加
        // 以下は共通処理
        // - 受注ステータスの変更（購入処理中 -> 決済処理中）
        // - 決済ステータス（なし -> 未決済）
        // - 在庫を減らす
        // - ポイントを減らす

        // PaymentMethod->apply に処理を移譲する
        // 別のコントローラに forward など
        $request = $this->requestStack->getCurrentRequest();

        return $method->apply($request);
    }
}