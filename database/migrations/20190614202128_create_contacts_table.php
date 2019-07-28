<?php

use \StudioAtual\Database\Migrations\MigrationBase;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends MigrationBase
{
    public function up()
    {
        $this->schema->create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 11);
            $table->string('city');
            $table->string('state', 2);
            $table->string('subject');
            $table->text('message');
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
        $this->schema->drop('contacts');
        /*
        $this->schema->table('', function (Blueprint $table) {

        });
        */
    }
}
