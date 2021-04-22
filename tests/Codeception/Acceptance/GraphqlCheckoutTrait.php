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

use Codeception\Util\HttpCode;
use Hkreuter\GraphQL\AdminGraph\Tests\Codeception\AcceptanceTester;
use TheCodingMachine\GraphQLite\Types\ID;

trait GraphqlCheckoutTrait
{
    protected function getGQLResponse(
        AcceptanceTester $I,
        string $query,
        array $variables = []
    ): array {
        $I->sendGQLQuery($query, $variables);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        return $I->grabJsonResponseAsArray();
    }

    protected function createBasket(AcceptanceTester $I, string $basketTitle): string
    {
        $variables = [
            'title' => $basketTitle,
        ];

        $query = '
            mutation ($title: String!){
                basketCreate(basket: {title: $title}) {
                    id
                }
            }
        ';
        $result = $this->getGQLResponse($I, $query, $variables);

        return $result['data']['basketCreate']['id'];
    }

    protected function addProductToBasket(AcceptanceTester $I, string $basketId, string $productId, float $amount): array
    {
        $variables = [
            'basketId'  => $basketId,
            'productId' => $productId,
            'amount'    => $amount,
        ];

        $mutation = '
            mutation ($basketId: ID!, $productId: ID!, $amount: Float! ) {
                basketAddItem(
                    basketId: $basketId,
                    productId: $productId,
                    amount: $amount
                ) {
                    id
                    items {
                        id
                        product {
                            id
                        }
                        amount
                    }
                }
            }
        ';

        $result = $this->getGQLResponse($I, $mutation, $variables);

        return $result['data']['basketAddItem']['items'];
    }

    protected function setBasketDeliveryMethod(
        AcceptanceTester $I,
        string $basketId,
        string $deliverySetId
    ): string {
        $variables = [
            'basketId'   => new ID($basketId),
            'deliveryId' => new ID($deliverySetId),
        ];

        $mutation = '
            mutation ($basketId: ID!, $deliveryId: ID!) {
                basketSetDeliveryMethod(
                    basketId: $basketId,
                    deliveryMethodId: $deliveryId
                    ) {
                    deliveryMethod {
                        id
                    }
                }
            }
        ';
        $result = $this->getGQLResponse($I, $mutation, $variables);

        if (isset($result['errors'])) {
            return (string) $result['errors'][0]['message'];
        }

        return (string) $result['data']['basketSetDeliveryMethod']['deliveryMethod']['id'];
    }

    protected function setBasketPaymentMethod(AcceptanceTester $I, string $basketId, string $paymentId): string
    {
        $variables = [
            'basketId'  => new ID($basketId),
            'paymentId' => new ID($paymentId),
        ];

        $mutation = '
            mutation ($basketId: ID!, $paymentId: ID!) {
                basketSetPayment(
                    basketId: $basketId,
                    paymentId: $paymentId
                    ) {
                    id
                }
            }
        ';
        $result = $this->getGQLResponse($I, $mutation, $variables);

        return $result['data']['basketSetPayment']['id'];
    }

    protected function placeOrder(AcceptanceTester $I, string $basketId, ?bool $termsAndConditions = null): array
    {
        //now actually place the order
        $variables = [
            'basketId'                  => new ID($basketId),
            'confirmTermsAndConditions' => $termsAndConditions,
        ];

        $mutation = '
            mutation ($basketId: ID!, $confirmTermsAndConditions: Boolean) {
                placeOrder(
                    basketId: $basketId
                    confirmTermsAndConditions: $confirmTermsAndConditions
                ) {
                    id
                    orderNumber
                }
            }
        ';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function createDeliveryAddress(AcceptanceTester $I, string $countryId = 'a7c40f631fc920687.20179984'): string
    {
        $variables = [
            'countryId' => new ID($countryId),
        ];

        $mutation = 'mutation ($countryId: ID!) {
                customerDeliveryAddressAdd(deliveryAddress: {
                    salutation: "MRS",
                    firstName: "Marlene",
                    lastName: "Musterlich",
                    additionalInfo: "protected delivery",
                    street: "Bertoldstrasse",
                    streetNumber: "48",
                    zipCode: "79098",
                    city: "Freiburg",
                    countryId: $countryId}
                    ){
                       id
                    }
                }
            ';

        $result = $this->getGQLResponse($I, $mutation, $variables);

        return $result['data']['customerDeliveryAddressAdd']['id'];
    }

    protected function setBasketDeliveryAddress(
        AcceptanceTester $I,
        string $basketId,
        string $deliveryAddressId
    ): array {
        $variables = [
            'basketId'          => $basketId,
            'deliveryAddressId' => $deliveryAddressId,
        ];

        $mutation = '
            mutation ($basketId: String!, $deliveryAddressId: String!) {
                basketSetDeliveryAddress(basketId: $basketId, deliveryAddressId: $deliveryAddressId) {
                    deliveryAddress {
                        id
                    }
                }
            }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function getLatestOrderFromOrderHistory(AcceptanceTester $I): array
    {
        $mutation = '
            query {
                customer {
                    id
                    orders(
                        pagination: {limit: 1, offset: 0}
                    ){
                        id
                        orderNumber
                        invoiceNumber
                        invoiced
                        cancelled
                        ordered
                        paid
                        updated
                        cost {
                            total
                            voucher
                            discount
                        }
                        vouchers {
                            id
                        }
                        invoiceAddress {
                            firstName
                            lastName
                            street
                            city
                        }
                        deliveryAddress {
                            firstName
                            lastName
                            street
                            city
                            country {
                                id
                            }
                        }
                    }
                }
            }
        ';

        $result = $this->getGQLResponse($I, $mutation);

        return $result['data']['customer']['orders'][0];
    }

    protected function getBasketPaymentIds(AcceptanceTester $I, string $basketId): array
    {
        $variables = [
            'basketId' => new ID($basketId),
        ];

        $query = 'query ($basketId: ID!) {
                     basketPayments(basketId:$basketId){
                         id
                     }
                }';

        $raw = $this->getGQLResponse($I, $query, $variables);

        $result = [];

        foreach ($raw['data']['basketPayments'] as $sub) {
            $result[$sub['id']] = $sub['id'];
        }

        return $result;
    }

    protected function registerCustomer(AcceptanceTester $I, string $email, string $password = 'useruser'): array
    {
        $variables = [
            'email'    => $email,
            'password' => $password,
            'name',
        ];

        $mutation = 'mutation ($email: String!, $password: String!) {
            customerRegister (
                customer: {
                    email:    $email,
                    password: $password
                }
            ) {
                id
                email
            }
        }';

        $result = $this->getGQLResponse($I, $mutation, $variables);

        return $result['data']['customerRegister'];
    }

    protected function setInvoiceAddress(AcceptanceTester $I): array
    {
        $variables = [
            'firstName'    => 'Test',
            'lastName'     => 'Registered',
            'street'       => 'Landstraße',
            'streetNumber' => '66',
            'zipCode'      => '22547',
            'city'         => 'Hamburg',
            'countryId'    => new ID('a7c40f631fc920687.20179984'),
        ];

        $mutation = 'mutation (
                  $firstName: String!,
                  $lastName: String!,
                  $street: String!,
                  $streetNumber: String!,
                  $zipCode: String!,
                  $city: String!,
                  $countryId: ID!
                ) {
                customerInvoiceAddressSet (
                    invoiceAddress: {
                        firstName: $firstName,
                        lastName: $lastName,
                        street: $street,
                        streetNumber: $streetNumber
                        zipCode: $zipCode,
                        city: $city,
                        countryId: $countryId
                    })
                {
                    firstName
                    lastName
                }
            }';

        $result = $this->getGQLResponse($I, $mutation, $variables);

        return $result['data']['customerInvoiceAddressSet'];
    }

    protected function removeItemFromBasket(
        AcceptanceTester $I,
        string $basketId,
        string $itemId,
        int $amount = 1
    ): array {
        $variables = [
            'basketId' => $basketId,
            'itemId'   => $itemId,
            'amount'   => $amount,
        ];

        $mutation = 'mutation ($basketId: ID!, $itemId: ID!, $amount: Int!) {
            basketRemoveItem(
                basketId: $basketId,
                itemId: $itemId,
                amount: $amount
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function addVoucherToBasket(
        AcceptanceTester $I,
        string $basketId,
        string $voucherNumber
    ): array {
        $variables = [
            'basketId'      => $basketId,
            'voucherNumber' => $voucherNumber,
        ];

        $mutation = 'mutation ($basketId: String!, $voucherNumber: String!) {
            basketAddVoucher (
                basketId: $basketId,
                voucherNumber: $voucherNumber
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function removeVoucherFromBasket(
        AcceptanceTester $I,
        string $basketId,
        string $voucherId
    ): array {
        $variables = [
            'basketId'  => $basketId,
            'voucherId' => $voucherId,
        ];

        $mutation = 'mutation ($basketId: String!, $voucherId: String!) {
            basketRemoveVoucher (
                basketId: $basketId,
                voucherId: $voucherId
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function getAdminUserData()
    {
        return \Codeception\Util\Fixtures::get('adminUser');
    }
}
