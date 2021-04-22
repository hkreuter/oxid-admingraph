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

/**
 * All rights reserved.
 * See LICENSE file for license details.
 */

return [
    'adminUser'    => [
        'userId'        => 'oxdefaultadmin',
        'userLoginName' => 'admin',
        'userPassword'  => 'admin',
        'userName'      => 'John',
        'userLastName'  => 'Doe',
    ],
    'userPassword' => 'useruser',
    'demoUserName' => 'user@oxid-esales.com',
    'product'      => [
        'id'                 => 'dc5ffdf380e15674b56dd562a7cb6aec',
        'title'              => 'Kuyichi leather belt JEVER',
        'amount'             => '4',
        'price'              => '119,60 €',
        'bruttoprice_single' => '29.9',
        'nettoprice_single'  => '25.13',
    ],
    'payment_id'   => 'oxidcashondel',
    'shipping'     => [
        'standard' => 'oxidstandard',
    ],
    'admin2group' => [
        'OXID'       => '_testusergroup',
        'oxobjectid' => 'oxdefaultadmin',
        'oxgroupsid' => 'admingraph',
    ],
    'usergroup' => [
        'OXID'       => 'oxadmingraph',
        'oxtitle'    => 'GraphQL Admin ',
        'oxtitle_1'  => 'GraphQL Admin ',
    ],
];
