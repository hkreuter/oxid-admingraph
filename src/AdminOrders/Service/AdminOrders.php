<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\Service;

use DateTimeInterface;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\DataType\AdminOrders as AdminOrdersDataType;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\Infrastructure\AdminOrders as AdminOrdersInfrastructure;

final class AdminOrders
{
    /** @var AdminOrdersInfrastructure */
    private $adminOrdersInfrastructure;

    public function __construct(
        AdminOrdersInfrastructure $adminOrdersInfrastructure
    ) {
        $this->adminOrdersInfrastructure = $adminOrdersInfrastructure;
    }

    public function getAdminOrders(?DateTimeInterface $dateFrom = null, ?DateTimeInterface $dateTo = null): AdminOrdersDataType
    {
        return $this->adminOrdersInfrastructure->getAdminOrders($dateFrom, $dateTo);
    }
}
