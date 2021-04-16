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

namespace Customize\GraphQL\Mutation;

use Doctrine\ORM\EntityManager;
use Eccube\Entity\ProductClass;
use Eccube\Repository\ProductClassRepository;
use GraphQL\Type\Definition\Type;
use Plugin\Api\GraphQL\Mutation;
use Plugin\Api\GraphQL\Types;

class UpdatePriceMutation implements Mutation
{
    /**
     * @var Types
     */
    private $types;

    /**
     * @var ProductClassRepository
     */
    private $productClassRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        Types $types,
        ProductClassRepository $productClassRepository,
        EntityManager $entityManager
    ) {
        $this->types = $types;
        $this->productClassRepository = $productClassRepository;
        $this->entityManager = $entityManager;
    }

    public function getName()
    {
        return 'updatePrice';
    }

    public function getMutation()
    {
        return  [
            'type' => $this->types->get(ProductClass::class),
            'args' => [
                'code' => [
                    'type' => Type::nonNull(Type::string()),
                ],
                'price02' => [
                    'type' => Type::nonNull(Type::int()),
                ],
            ],
            'resolve' => [$this, 'updatePrice'],
        ];
    }

    public function updatePrice($root, $args)
    {
        $ProductClasses = $this->productClassRepository->findBy(['code' => $args['code']]);
        /** @var ProductClass $ProductClass */
        $ProductClass = current($ProductClasses);
        $ProductClass->setPrice02($args['price02']);
        $this->entityManager->flush();
        return $ProductClass;
    }
}
