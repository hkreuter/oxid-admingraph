<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\Controller;

use DateTimeInterface;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\DataType\AdminOrders as AdminOrdersDataType;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\Service\AdminOrders as AdminOrdersService;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class AdminOrders
{
    /** @var AdminOrdersService */
    private $adminOrdersService;

    public function __construct(
        AdminOrdersService $adminOrdersService
    ) {
        $this->adminOrdersService = $adminOrdersService;
    }

    /**
     * @Query()
     * @Logged()
     * @Right("ADMIN_GRAPHQL")
     */
    public function adminOrders(?DateTimeInterface $dateFrom = null, ?DateTimeInterface $dateTo = null): AdminOrdersDataType
    {
        return $this->adminOrdersService->getAdminOrders($dateFrom, $dateTo);
    }
}
