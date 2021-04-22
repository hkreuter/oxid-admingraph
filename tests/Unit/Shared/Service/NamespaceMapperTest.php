<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\Tests\Unit\Shared\Service;

use Hkreuter\GraphQL\AdminGraph\Shared\Service\NamespaceMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers Hkreuter\GraphQL\AdminGraph\Shared\Service\NamespaceMapper
 */
final class NamespaceMapperTest extends TestCase
{
    public function testNamespaceMapperHasEntries(): void
    {
        $namespaceMapper = new NamespaceMapper();
        $this->assertCount(
            1,
            $namespaceMapper->getControllerNamespaceMapping()
        );
        $this->assertCount(
            2,
            $namespaceMapper->getTypeNamespaceMapping()
        );
    }
}
