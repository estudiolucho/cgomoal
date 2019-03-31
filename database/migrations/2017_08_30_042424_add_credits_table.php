<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            
            $table->date('fecha_desembolso');
            $table->float('valor_desembolso',10,1);
            $table->float('tasa_mensual',2,1);
            $table->integer('cuotas')->nullable();
            $table->float('valor_cuota',10,1)->nullable();
            $table->float('saldo_interes',10,1);
            $table->float('saldo_capital',10,1);
            $table->boolean('estado')->default(true);
            $table->string('descripcion',120)->nullable();
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
        Schema::dropIfExists('credits');
    }
}
