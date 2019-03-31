<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->integer('role_id')->unsigned();

            $table->foreign('role_id')->references('id')->on('roles');

            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->enum('type',['socio','cliente'])->default('cliente');
            $table->string('main_addr');
            $table->string('main_phone');
            $table->string('secondary_addr')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('referrer')->nullable();
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
