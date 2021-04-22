<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\Service;

use Hkreuter\GraphQL\AdminGraph\AdminOrders\DataType\AdminOrders as AdminOrdersDataType;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\Infrastructure\AdminOrders as AdminOrdersInfrastructure;
use OxidEsales\GraphQL\Storefront\Order\DataType\Order as OrderDataType;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=AdminOrdersDataType::class)
 */
final class AdminOrdersRelations
{
    /** @var AdminOrdersInfrastructure */
    private $adminOrdersInfrastructure;

    public function __construct(
        AdminOrdersInfrastructure $adminOrdersInfrastructure
    ) {
        $this->adminOrdersInfrastructure = $adminOrdersInfrastructure;
    }

    /**
     * @Field()
     *
     * @return OrderDataType[]
     */
    public function orders(AdminOrdersDataType $adminOrders): array
    {
        return $this->adminOrdersInfrastructure->getOrders($adminOrders->getDateFrom(), $adminOrders->getDateTo());
    }
}
