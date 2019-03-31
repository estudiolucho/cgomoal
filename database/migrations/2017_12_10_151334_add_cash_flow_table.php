<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->double('amount',9);
            $table->enum('concept',['abono','credito','aporte','retiro_aporte','gasto','ajuste_ent','ajuste_sal']);//->default('cliente');
            $table->enum('type',['entrada','salida']);
            $table->double('balance',10)->nullable();
            $table->string('description',120)->nullable();
            $table->string('user_create')->nullable();
            $table->string('user_update')->nullable();
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
        Schema::dropIfExists('cash_flow');
    }
}
