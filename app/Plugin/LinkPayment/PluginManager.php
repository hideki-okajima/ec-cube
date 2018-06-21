<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/21
 * Time: 13:44
 */

namespace Plugin\LinkPayment;


use Eccube\Entity\Payment;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\PaymentRepository;
use Plugin\LinkPayment\Service\CreditCard;
use Plugin\LinkPayment\Service\PaymentService;
use Symfony\Component\DependencyInjection\ContainerInterface;

// TODO docコメントを充実させる
class PluginManager extends AbstractPluginManager
{
    public function enable($config, $app, ContainerInterface $container)
    {
        // TODO PluginServiceでインスタンス化されメソッドが呼ばれるので、Injectionできない.
        $paymentRepository = $container->get(PaymentRepository::class);
        $Payment = $paymentRepository->findOneBy(['method_class' => CreditCard::class]);
        if ($Payment) {
            return;
        }

        $Payment = new Payment();
        $Payment->setCharge(0);
        $Payment->setSortNo(999);
        $Payment->setVisible(true);
        $Payment->setMethod('サンプル決済(リンク)'); // todo nameでいいんじゃないか
        $Payment->setServiceClass(PaymentService::class);
        $Payment->setMethodClass(CreditCard::class);

        $entityManager = $container->get('doctrine.orm.entity_manager');
        $entityManager->persist($Payment);
        $entityManager->flush($Payment);
    }

}