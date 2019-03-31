<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserCreateUpdateToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('contributions', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('expenses', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('expense_concepts', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('contribution_concepts', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
        Schema::table('roles', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',50)->nullable();
            $table->string('user_update',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('contribution_concepts', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('expense_concepts', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
    }
}
