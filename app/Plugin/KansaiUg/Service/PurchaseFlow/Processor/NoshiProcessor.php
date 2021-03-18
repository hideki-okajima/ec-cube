<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\KansaiUg\Service\PurchaseFlow\Processor;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Eccube\Annotation\ShoppingFlow;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Repository\Master\OrderItemTypeRepository;
use Eccube\Repository\Master\TaxDisplayTypeRepository;
use Eccube\Repository\Master\TaxTypeRepository;
use Eccube\Repository\TaxRuleRepository;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;

/**
 * Class NoshiProcessor
 * @package Plugin\KansaiUg\Service\PurchaseFlow\Processor
 *
 * @ShoppingFlow
 */
class NoshiProcessor implements ItemHolderPreprocessor
{
    /**
     * @var OrderItemTypeRepository
     */
    private $orderItemTypeRepository;
    /**
     * @var TaxDisplayTypeRepository
     */
    private $taxDisplayTypeRepository;
    /**
     * @var TaxTypeRepository
     */
    private $taxTypeRepository;
    /**
     * @var TaxRuleRepository
     */
    private $taxRuleRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        OrderItemTypeRepository $orderItemTypeRepository,
        TaxDisplayTypeRepository $taxDisplayTypeRepository,
        TaxTypeRepository $taxTypeRepository,
        TaxRuleRepository $taxRuleRepository,
        EntityManager $entityManager
    ) {
        $this->orderItemTypeRepository = $orderItemTypeRepository;
        $this->taxDisplayTypeRepository = $taxDisplayTypeRepository;
        $this->taxTypeRepository = $taxTypeRepository;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->entityManager = $entityManager;
    }

    public function process(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        // Cart では処理をしない
        if (!$itemHolder instanceof Order) {
            return;
        }

        // すでに設定した「熨斗手数料」があれば削除
        foreach ($itemHolder->getItems() as $item) {
            if ($item->getProcessorName() == NoshiProcessor::class) {
                $itemHolder->removeOrderItem($item);
                $this->entityManager->remove($item);

                break;
            }
        }

        // 熨斗が有効な場合に熨斗手数料の明細を追加
        if ($itemHolder->isKansaiUgNoshi()) {
            $this->addNoshiItem($itemHolder);
        }
    }

    /**
     * @param ItemHolderInterface $itemHolder
     * @throws NoResultException
     */
    private function addNoshiItem(ItemHolderInterface $itemHolder): void
    {
        $OrderItemType = $this->orderItemTypeRepository->find(OrderItemType::CHARGE);
        $TaxDisplayType = $this->taxDisplayTypeRepository->find(TaxDisplayType::INCLUDED);
        $Taxation = $this->taxTypeRepository->find(TaxType::TAXATION);
        $TaxRule = $this->taxRuleRepository->getByRule();

        $item = new OrderItem();
        $item->setProductName('熨斗手数料')
            ->setQuantity(1)
            ->setPrice(500)
            ->setOrderItemType($OrderItemType)
            ->setOrder($itemHolder)
            ->setTaxDisplayType($TaxDisplayType)
            ->setTaxType($Taxation)
            ->setProcessorName(NoshiProcessor::class)
            ->setTaxRate($TaxRule->getTaxRate())
            ->setTaxAdjust($TaxRule->getTaxAdjust())
            ->setRoundingType($TaxRule->getRoundingType());

        $itemHolder->addItem($item);
    }
}
