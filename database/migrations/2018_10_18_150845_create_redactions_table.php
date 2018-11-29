<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry')->unsigned()->unique();
            $table->string('file');
            $table->enum('status',
                ['Digitalizada','Para correção','Corrigida (1x)', 'Corrigida (concluído)', 'Inconsistência', ]
            )->default('Digitalizada');
            $table->double('final_score', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Tabela Pivô - Avaliadores X Redações
        Schema::create('corrector_redaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('corrector_id')->unsigned();
            $table->integer('redaction_id')->unsigned();
            $table->double('score', 5, 2);
            $table->timestamps();
            $table->foreign('corrector_id')->references('id')->on('correctors')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('redaction_id')->references('id')->on('redactions')->onUpdate('cascade')->onDelete('restrict');
        });

        // Tabela Pivô - Lotes X Redações
        Schema::create('lot_redaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lot_id')->unsigned();
            $table->integer('redaction_id')->unsigned();
            $table->timestamps();
            $table->foreign('lot_id')->references('id')->on('lots')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('redaction_id')->references('id')->on('redactions')->onUpdate('cascade')->onDelete('restrict');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrector_redaction', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
            $table->dropForeign(['redaction_id']);
        });
        Schema::table('lot_redaction', function (Blueprint $table){
            $table->dropForeign(['lot_id']);
            $table->dropForeign(['redaction_id']);
        });
        Schema::dropIfExists('corrector_redaction');
        Schema::dropIfExists('lot_redaction');
        Schema::dropIfExists('redactions');
    }
}
