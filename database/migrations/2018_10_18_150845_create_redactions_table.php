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
            $table->integer('lot_id')->unsigned()->nullable();
            $table->foreign('lot_id')->references('id')->on('lots')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
        // Tabela Pivô - Avaliadores X Redações
        Schema::create('correctors_redactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('corrector_id')->unsigned();
            $table->integer('redaction_id')->unsigned();
            $table->double('score', 5, 2);
            $table->timestamps();
            $table->foreign('corrector_id')->references('id')->on('correctors')->onUpdate('cascade')->onDelete('restrict');
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
        Schema::table('correctors_redactions', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
            $table->dropForeign(['redaction_id']);
        });
        Schema::dropIfExists('correctors_redactions');

        Schema::table('redactions', function (Blueprint $table){
            $table->dropForeign(['lot_id']);
        });
        Schema::dropIfExists('redactions');
    }
}
