<?php


namespace Customize;


use Eccube\Entity\Master\OrderStatus;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SalesEvent implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::ADMIN_ADMIM_INDEX_SALES => 'onAdminAdminIndexSales',
        ];
    }

    public function onAdminAdminIndexSales (EventArgs $event){
        $excludes = $event->getArgument('excludes');
        // 管理画面ダッシュボードの集計対象から対応状況が「新規受付」の受注を除外する
        $excludes[] = OrderStatus::NEW;
        $event->setArgument('excludes', $excludes);
    }
}
