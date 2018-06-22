<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 15:41
 */

namespace Plugin\LinkPayment\Controller;

use Eccube\Annotation\ForwardOnly;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Order;
use Eccube\Repository\OrderRepository;
use Eccube\Service\ShoppingService;
use Plugin\LinkPayment\Entity\PaymentStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends AbstractController
{

    /**
     * @var OrderRepository
     */
    protected $orderRepository;
    /**
     * @var ShoppingService
     */
    private $shoppingService;

    /**
     * PaymentController constructor.
     * @param OrderRepository $orderRepository
     * @param ShoppingService $shoppingService
     */
    public function __construct(OrderRepository $orderRepository, ShoppingService $shoppingService)
    {
        $this->orderRepository = $orderRepository;
        $this->shoppingService = $shoppingService;
    }


    /**
     * @ForwardOnly
     * @Route("sample_payment_index", name="sample_payment_index")
     */
    public function index()
    {
//        /** @var Order $Order */
        $Order = $this->shoppingService->getOrder();
//
//        if (!$Order) {
//            // TODO エラー処理
//        }
//
//        dump($Order);
//        // TODO 決済会社の共通処理はPaymentServiceのdispatchで処理すべきなので移植が必要
//        // - 受注ステータスの変更（購入処理中 -> 決済処理中）
//        $this->shoppingService->setOrderStatus($Order, OrderStatus::PENDING);
//dump($Order);
//
//        // - 決済ステータス（なし -> 未決済）
//        // TODO DBにレコードを追加する必要がある
//        if ($Order->getLinkPaymentPaymentStatus() == null) {
//            $PaymentStatus = $this->entityManager->find(PaymentStatus::class, PaymentStatus::OUTSTANDING);
//            $Order->setLinkPaymentPaymentStatus($PaymentStatus);
//        }
//
//        // TODO ここでflushはさせたくない
//        $this->entityManager->persist($Order);
//        $this->entityManager->flush($Order);
//        dump($Order);exit;

        // 決済会社の決済画面へのリンク
        $url = '/payment_company';

        // 注文番号を付与
        $orderCode = $Order->getOrderCode();
        $url .= '?code=' . $orderCode;

        return new RedirectResponse($url);

    }

    /**
     * @Route("/sample_payment_back", name="sample_payment_back")
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
    public function complete(Request $request)
    {
        $orderCode = $request->get('code');

        // カード情報を保存するなどあればここに処理を追加


        // TODO 受注番号を完了画面に送って画面に表示させたい
        return $this->redirectToRoute("shopping_complete");
    }

    /**
     * @Route("/sample_payment_receive_complete", name="sample_payment_receive_complete")
     * @Method("POST")
     *
     * TODO この処理は本体に移動させたい
     */
    public function receiveComplete(Request $request)
    {
        // 決済会社から受注番号を受け取る
        $orderCode = $request->get('code');

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
