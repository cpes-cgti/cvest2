<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrectionLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status',
                ['Criado','Correção em andamento','Finalizado', ]
            )->default('Criado');
            $table->integer('corrector_id')->unsigned()->nullable();
            $table->foreign('corrector_id')->references('id')->on('correctors')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela Pivô - Avaliadores X Lotes
        /* Schema::create('corrector_lot', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('corrector_id')->unsigned();
            $table->integer('lot_id')->unsigned();
            $table->timestamps();
            $table->foreign('corrector_id')->references('id')->on('correctors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('lot_id')->references('id')->on('lots')->onUpdate('cascade')->onDelete('restrict');
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Schema::table('corrector_lot', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
            $table->dropForeign(['lot_id']);
        });
        Schema::dropIfExists('corrector_lot'); */

        Schema::table('lots', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
        });

        Schema::dropIfExists('lots');
    }
}
