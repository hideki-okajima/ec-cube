<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 15:41
 */

namespace Plugin\LinkPayment\Controller;


use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends AbstractController
{
    /**
     * @Route("/sample_payment_back", name="sample_payment_back")
     * @Method("POST")
     */
    public function back()
    {
        // TODO 以下の処理を追加
        // 受注ステータスを戻す（決済処理中 -> 購入処理中）
        // 在庫を戻す
        // ポイントを戻す
        return $this->redirectToRoute("shopping");
    }

    /**
     * @Route("/sample_payment_complete", name="sample_payment_complete")
     */
    public function complete()
    {
        // カード情報を保存するなどあればここで
        return $this->redirectToRoute("shopping_complete");
    }

    /**
     * @Route("/sample_payment_receive_complete", name="sample_payment_receive_complete")
     * @Method("POST")
     */
    public function receiveComplete()
    {
        // TODO 以下の処理を追加
        // 独自処理
        // 完了通知リクエストのパラメータの正当性チェック（EC-CUBEの注文番号、）
        // 受注ステータス更新（決済処理中 -> 新規受付）
        // 決済ステータス更新（未決済 -> 仮売上済み）
        // 決済会社に結果を返す（200 or それ以外）

        // 共通処理
        // 完了メール送信
        // 残っていればカート削除
        return new Response("OK!!");
    }
}
