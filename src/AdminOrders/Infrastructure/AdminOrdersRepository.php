<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\AdminOrders\Infrastructure;

use DateTimeInterface;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use PDO;

final class AdminOrdersRepository
{
    /** @var QueryBuilderFactoryInterface */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getOrdersCount(?DateTimeInterface $dateFrom, ?DateTimeInterface $dateTo): int
    {
        $parameters = [];

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->getConnection()->setFetchMode(PDO::FETCH_ASSOC);
        $queryBuilder->select('count(oxorder.oxid) as counted')
            ->from('oxorder');

        $method = 'where';

        if ($dateFrom) {
            $queryBuilder->where('oxorder.OXORDERDATE >= :dateFrom');
            $parameters[':dateFrom'] = $dateFrom->format('Y-m-d h:i:s');
            $method                  = 'andWhere';
        }

        if ($dateTo) {
            $parameters[':dateTo'] = $dateTo->format('Y-m-d h:i:s');
            $queryBuilder->$method('oxorder.OXORDERDATE <= :dateTo');
        }

        $queryBuilder->setParameters($parameters);

        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();

        return (int) $result->fetchOne();
    }

    public function getOrderIds(?DateTimeInterface $dateFrom, ?DateTimeInterface $dateTo): array
    {
        $parameters = [];

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->getConnection()->setFetchMode(PDO::FETCH_ASSOC);
        $queryBuilder->select('oxorder.oxid')
            ->from('oxorder');

        $method = 'where';

        if ($dateFrom) {
            $queryBuilder->where('oxorder.OXORDERDATE >= :dateFrom');
            $parameters[':dateFrom'] = $dateFrom->format('Y-m-d h:i:s');
            $method                  = 'andWhere';
        }

        if ($dateTo) {
            $parameters[':dateTo'] = $dateTo->format('Y-m-d h:i:s');
            $queryBuilder->$method('oxorder.OXORDERDATE <= :dateTo');
        }

        $queryBuilder->setParameters($parameters);

        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();

        return $result->fetchAll(FetchMode::COLUMN);
    }
}
