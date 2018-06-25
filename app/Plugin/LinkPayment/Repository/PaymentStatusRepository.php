<?php
/**
 * Created by PhpStorm.
 * User: hideki_okajima
 * Date: 2018/06/25
 * Time: 13:54
 */

namespace Plugin\LinkPayment\Repository;


use Doctrine\Common\Persistence\ManagerRegistry;
use Eccube\Repository\AbstractRepository;
use Plugin\LinkPayment\Entity\PaymentStatus;

class PaymentStatusRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentStatus::class);
    }
}