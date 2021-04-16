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

namespace Customize\GraphQL\Query;

use Eccube\Entity\News;
use Eccube\Repository\NewsRepository;
use GraphQL\Type\Definition\Type;
use Plugin\Api\GraphQL\Query;
use Plugin\Api\GraphQL\Types;

class NewsQuery implements Query
{
    /**
     * @var Types
     */
    private $types;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        Types $types,
        NewsRepository $newsRepository
    ) {
        $this->types = $types;
        $this->newsRepository = $newsRepository;
    }

    public function getName()
    {
        return 'news';
    }

    public function getQuery()
    {
        return [
            'type' => $this->types->get(News::class),
            'args' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                ],
            ],
            'resolve' => function ($root, $args) {
                // 処理を追加
                return $this->newsRepository->find($args['id']);
            },
        ];
    }
}
