<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\Shared\Service;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\Hkreuter\\GraphQL\\AdminGraph\\AdminOrders\\Controller' => __DIR__ . '/../../AdminOrders/Controller/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\Hkreuter\\GraphQL\\AdminGraph\\AdminOrders\\DataType' => __DIR__ . '/../../AdminOrders/DataType/',
            '\\Hkreuter\\GraphQL\\AdminGraph\\AdminOrders\\Service'  => __DIR__ . '/../../AdminOrders/Service/',
        ];
    }
}
