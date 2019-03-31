<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserCreateUpdateCredits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('payments', function (Blueprint $table) {
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
        
        //ejemplo de borrar campo a tabla
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('user_create','user_update');
        });
    }
}
