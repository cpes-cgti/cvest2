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
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela Pivô - Avaliadores X Lotes
        Schema::create('correctors_lots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('corrector_id')->unsigned();
            $table->integer('lot_id')->unsigned();
            $table->timestamps();
            $table->foreign('corrector_id')->references('id')->on('correctors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('lot_id')->references('id')->on('lots')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('correctors_lots', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
            $table->dropForeign(['lot_id']);
        });
        Schema::dropIfExists('correctors_lots');

        Schema::dropIfExists('lots');
    }
}
