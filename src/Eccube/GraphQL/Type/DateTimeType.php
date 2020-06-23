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

namespace Eccube\GraphQL\Type;

use DateTime;
use DateTimeImmutable;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class DateTimeType extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'DateTime';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents time data, represented as an ISO-8601 encoded UTC date string.';

    /**
     * @param mixed $value
     *
     * @return string
     *
     * @throws Error
     */
    public function serialize($value)
    {
        if (!$value instanceof DateTimeImmutable) {
            throw new Error('DateTime is not an instance of DateTimeImmutable: '.Utils::printSafe($value));
        }

        return $value->format(DateTime::ATOM);
    }

    /**
     * @param mixed $value
     *
     * @return DateTimeImmutable|false|null
     */
    public function parseValue($value)
    {
        return DateTimeImmutable::createFromFormat(DateTime::ATOM, $value) ?: null;
    }

    /**
     * @param Node $valueNode
     * @param array|null $variables
     *
     * @return string|null
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }

        return null;
    }
}
