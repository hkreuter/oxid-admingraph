<?php
/**
 * All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Hkreuter\GraphQL\AdminGraph\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210606121212 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(
            "REPLACE INTO `oxgroups` (`oxid`,`oxactive`,`oxtitle`,`oxtitle_1`)
             VALUES ('oxadmingraph', '1', 'GraphQL Admin', 'GraphQL Admin');"
        );
    }

    public function down(Schema $schema): void
    {
    }
}
