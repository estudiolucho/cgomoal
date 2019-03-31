<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credit_id')->unsigned();
            $table->integer('document')->unsigned();

            $table->foreign('credit_id')->references('id')->on('credits');
                        
            $table->date('date_payment');
            $table->string('descripcion',120)->nullable();
            $table->double('amount',9);
            $table->double('intmora',9)->nullable();
            $table->double('saldointmora',9)->nullable();
            $table->double('abono_interes',9);
            $table->double('saldo_interes',9)->nullable();
            $table->double('abono_capital',9);
            $table->double('saldo_capital',9)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
