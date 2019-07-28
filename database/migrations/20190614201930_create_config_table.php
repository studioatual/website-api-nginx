<?php

use \StudioAtual\Database\Migrations\MigrationBase;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends MigrationBase
{
    public function up()
    {
        $this->schema->create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company');
            $table->string('email1');
            $table->string('email2');
            $table->string('analytics');
            $table->string('title');
            $table->string('description');
            $table->string('keywords');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        /*
        $this->schema->table('', function (Blueprint $table) {

        });
        */
    }

    public function down()
    {
        $this->schema->drop('config');
        /*
        $this->schema->table('', function (Blueprint $table) {

        });
        */
    }
}
