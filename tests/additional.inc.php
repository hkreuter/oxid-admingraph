<?php

/**
 * Copyright © hkreuter. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

# Set default user ID to fit expectation of old tests.
\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("UPDATE oxuser SET OXID='oxdefaultadmin' WHERE oxusername='admin'");

define('oxADMIN_LOGIN', oxDb::getDb()->getOne("select OXUSERNAME from oxuser where oxid='oxdefaultadmin'"));
define('oxADMIN_PASSWD', getenv('oxADMIN_PASSWD') ? getenv('oxADMIN_PASSWD') : 'admin');
