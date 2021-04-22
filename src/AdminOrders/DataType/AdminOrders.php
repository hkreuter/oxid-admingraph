<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\DataType;

use DateTimeInterface;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class AdminOrders
{
    /** @var ?DateTimeInterface */
    private $dateFrom;

    /** @var ?DateTimeInterface */
    private $dateTo;

    /** @var int */
    private $orderCount;

    public function __construct(
        ?DateTimeInterface $dateFrom,
        ?DateTimeInterface $dateTo,
        Int $orderCount
    ) {
        $this->dateFrom   = $dateFrom;
        $this->dateTo     = $dateTo;
        $this->orderCount = $orderCount;
    }

    /**
     * @Field
     */
    public function getDateFrom(): ?DateTimeInterface
    {
        return $this->dateFrom;
    }

    /**
     * @Field
     */
    public function getDateTo(): ?DateTimeInterface
    {
        return $this->dateTo;
    }

    /**
     * @Field
     */
    public function getOrderCount(): int
    {
        return $this->orderCount;
    }
}
