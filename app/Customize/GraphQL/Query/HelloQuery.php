<?php

namespace Customize\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Plugin\Api\GraphQL\Query;

class HelloQuery implements Query
{
    public function getName()
    {
        return 'hello';
    }

    public function getQuery()
    {
        return [
            'type' => Type::string(),
            'resolve' => function ($root) {
                return 'Hello Query!!!!!!';
            },
        ];
    }
}
