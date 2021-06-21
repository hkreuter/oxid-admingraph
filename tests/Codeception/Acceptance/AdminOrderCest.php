<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

/**
 * All rights reserved.
 * See LICENSE file for license details.
 */

namespace Hkreuter\GraphQL\AdminGraph\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use Hkreuter\GraphQL\AdminGraph\Tests\Codeception\AcceptanceTester;

/**
 * @group admingraph
 */
final class AdminOrderCest
{
    use GraphqlCheckoutTrait;

    public const DEMO_ORDER_CREATED = '2011-03-30 10:55:13';

    public function _after(AcceptanceTester $I): void
    {
        $I->deleteFromDatabase('oxobject2group', ['OXID' => 'oxadmingraph']);
    }

    public function testCannotQueryAdminOrdersWhenNotLoggedIn(AcceptanceTester $I): void
    {
        $I->wantToTest('cannot query adminOrders without token');

        $result = $this->queryAdminOrders($I);

        $I->assertSame(
            'Cannot query field "adminOrders" on type "Query".',
            $result['errors'][0]['message']
        );
    }

    public function testCannotQueryAdminOrdersAsAnonymousUser(AcceptanceTester $I): void
    {
        $I->wantToTest('cannot Query adminOrders as anonymous user');

        $I->anonymousLoginToGraphQLApi();

        $result = $this->queryAdminOrders($I);

        $I->assertSame(
            'Cannot query field "adminOrders" on type "Query".',
            $result['errors'][0]['message']
        );
    }

    public function testCannotQueryAdminOrdersAsNormalUser(AcceptanceTester $I): void
    {
        $I->wantToTest('cannot Query adminOrders as normal user');

        $I->loginToGraphQLApi(Fixtures::get('demoUserName'), Fixtures::get('userPassword'));

        $result = $this->queryAdminOrders($I);

        $I->assertSame(
            'Cannot query field "adminOrders" on type "Query".',
            $result['errors'][0]['message']
        );
    }

    public function testCannotQueryAdminOrdersAsAdminOfWrongGroup(AcceptanceTester $I): void
    {
        $I->wantToTest('cannot Query adminOrders as admin who is not in group oxadmingraph');

        $I->loginToGraphQLApi(Fixtures::get('adminUser')['userLoginName'], Fixtures::get('adminUser')['userPassword']);

        $result = $this->queryAdminOrders($I);

        $I->assertSame(
            'Cannot query field "adminOrders" on type "Query".',
            $result['errors'][0]['message']
        );
    }

    public function testCanQueryAdminOrdersAsAdminOfCorrectGroup(AcceptanceTester $I): void
    {
        $I->wantToTest('can query adminOrders as admin who is in group oxadmingraph');

        $I->loginToGraphQLApi(Fixtures::get('adminUser')['userLoginName'], Fixtures::get('adminUser')['userPassword']);

        $result = $this->queryAdminOrders($I, '2010-03-30', '2012-03-30');
        $I->assertSame(
            'Cannot query field "adminOrders" on type "Query".',
            $result['errors'][0]['message']
        );

        //add admin to required group
        $this->setAdminGroup($I);

        $result = $this->queryAdminOrders($I, '2010-03-30', '2012-03-30');
        $I->assertEquals(1, $result['data']['adminOrders']['orderCount']);

        $result = $this->queryAdminOrders($I, '2015-03-30', '2016-03-30');
        $I->assertEquals(0, $result['data']['adminOrders']['orderCount']);
    }

    public function testAdminOrderCountWhenAdmin(AcceptanceTester $I): void
    {
        $I->wantToTest('Admin querying total order count');

        $this->setAdminGroup($I);
        $I->loginToGraphQLApi(Fixtures::get('adminUser')['userLoginName'], Fixtures::get('adminUser')['userPassword']);

        $result = $this->queryAdminOrders($I);
        $before = $result['data']['adminOrders']['orderCount'];

        $this->createOrderWithGraphql($I);

        $I->loginToGraphQLApi(Fixtures::get('adminUser')['userLoginName'], Fixtures::get('adminUser')['userPassword']);

        $result = $this->queryAdminOrders($I);
        $I->assertSame($before + 1, $result['data']['adminOrders']['orderCount']);

        $result = $this->queryAdminOrders($I, '2010-03-30', null);
        $I->assertEquals(2, $result['data']['adminOrders']['orderCount']);

        $result = $this->queryAdminOrders($I, null, '2016-03-30');
        $I->assertEquals(1, $result['data']['adminOrders']['orderCount']);
    }

    protected function queryAdminOrders(AcceptanceTester $I, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $queryPart  = '';

        if ($dateFrom) {
            $queryPart .= 'dateFrom: "' . $dateFrom . '"' . PHP_EOL;
        }

        if ($dateTo) {
            $queryPart .= 'dateTo: "' . $dateTo . '"' . PHP_EOL;
        }

        if ($queryPart) {
            $queryPart  = '(' . $queryPart . ')';
        }

        $query = 'query {
                     adminOrders ' . $queryPart . '{
                        orderCount
                        orders {
                              id
                              orderNumber
                        }
                     }
                }';

        return $this->getGQLResponse($I, $query, []);
    }

    protected function createOrderWithGraphql(AcceptanceTester $I): void
    {
        $I->loginToGraphQLApi(Fixtures::get('demoUserName'), Fixtures::get('userPassword'));

        //prepare basket
        $basketId = $this->createBasket($I, 'my_cart_one');
        $this->addProductToBasket($I, $basketId, Fixtures::get('product')['id'], 2);
        $this->setBasketDeliveryMethod($I, $basketId, Fixtures::get('shipping')['standard']);
        $this->setBasketPaymentMethod($I, $basketId, Fixtures::get('payment_id'));

        //place the order
        $result  = $this->placeOrder($I, $basketId);
        $orderId = $result['data']['placeOrder']['id'];

        $I->assertNotEmpty($orderId);
        $I->logoutFromGraphQLApi();
    }

    protected function setAdminGroup(AcceptanceTester $I): void
    {
        $I->haveInDatabase(
            'oxobject2group',
            [
                'OXID'       => 'oxidadmingraph',
                'OXSHOPID'   => 1,
                'OXOBJECTID' => Fixtures::get('adminUser')['userId'],
                'OXGROUPSID' => 'oxadmingraph',
            ]
        );

        $I->haveInDatabase(
            'oxobject2group',
            [
                'OXID'       => 'oxidadmin',
                'OXSHOPID'   => 1,
                'OXOBJECTID' => Fixtures::get('adminUser')['userId'],
                'OXGROUPSID' => 'oxidadmin',
            ]
        );
    }
}
