<?php

use \StudioAtual\Database\Migrations\MigrationBase;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends MigrationBase
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('username', 40)->unique();
            $table->string('password');
            $table->string('hash')->nullable();
            $table->boolean('admin')->default(false);
            $table->datetime('access')->nullable();
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
        $this->schema->drop('users');
        /*
        $this->schema->table('', function (Blueprint $table) {

        });
        */
    }
}
