<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\Infrastructure;

use DateTimeInterface;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\DataType\AdminOrders as AdminOrdersDataType;
use Hkreuter\GraphQL\AdminGraph\AdminOrders\Infrastructure\AdminOrdersRepository as OrderRepository;
use OxidEsales\GraphQL\Storefront\Order\DataType\Order as OrderDataType;
use OxidEsales\GraphQL\Storefront\Shared\Infrastructure\Repository as SharedRepository;

final class AdminOrders
{
    /** @var OrderRepository */
    private $orderRepository;

    /** @var SharedRepository */
    private $sharedRepository;

    public function __construct(
        OrderRepository $orderRepository,
        SharedRepository $sharedRepository
    ) {
        $this->orderRepository  = $orderRepository;
        $this->sharedRepository = $sharedRepository;
    }

    public function getAdminOrders(?DateTimeInterface $dateFrom = null, ?DateTimeInterface $dateTo = null): AdminOrdersDataType
    {
        $count = $this->orderRepository->getOrdersCount($dateFrom, $dateTo);

        return new AdminOrdersDataType($dateFrom, $dateTo, $count);
    }

    /**
     * @return OrderDataType[]
     */
    public function getOrders(?DateTimeInterface $dateFrom = null, ?DateTimeInterface $dateTo = null): array
    {
        $orders   = [];
        $orderIds = $this->orderRepository->getOrderIds($dateFrom, $dateTo);

        foreach ($orderIds as $id) {
            $orders[] = $this->sharedRepository->getById($id, OrderDataType::class);
        }

        return $orders;
    }
}
