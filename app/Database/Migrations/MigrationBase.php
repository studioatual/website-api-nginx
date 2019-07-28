<?php

namespace StudioAtual\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class MigrationBase extends AbstractMigration
{
    protected $schema;

    public function init()
    {
        $this->schema = (new Capsule)->schema();
    }
}