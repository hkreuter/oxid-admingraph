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

// This is acceptance bootstrap
use OxidEsales\Facts\Facts;
use Webmozart\PathUtil\Path;

$facts  = new Facts();
$helper = new \OxidEsales\Codeception\Module\FixturesHelper();
$helper->loadRuntimeFixtures(Path::join(__DIR__, '..', '_data', 'fixtures.php'));
