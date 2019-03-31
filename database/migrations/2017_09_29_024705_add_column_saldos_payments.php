<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSaldosPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('payments', function (Blueprint $table) {
            //ejemplo de agregar campo a tabla
            $table->string('user_create',9)->nullable();
            $table->string('user_update',9)->nullable();
            //$table->double('saldo_capital',9)->nullable();
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        //ejemplo de borrar campo a tabla
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
        */
    }
}
