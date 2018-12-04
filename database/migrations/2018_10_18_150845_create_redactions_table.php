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
                ['Digitalizada','Para correção','Corrigida (1x)', 'Corrigida (concluído)', 'Necessita revisão', ]
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
            $table->integer('lot')->unsigned()->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->double('score', 5, 2)->nullable();
            $table->boolean('zero_empty')->default(false);
            $table->boolean('zero_identification')->default(false);
            $table->boolean('zero_theme')->default(false);
            $table->boolean('zero_lines')->default(false);
            $table->boolean('zero_offensive_content')->default(false);
            $table->double('competenceA', 5, 2)->nullable();
            $table->double('competenceB', 5, 2)->nullable();
            $table->double('competenceC', 5, 2)->nullable();
            $table->double('competenceD', 5, 2)->nullable();
            $table->text('note')->nullable();
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
        Schema::table('corrector_redaction', function (Blueprint $table){
            $table->dropForeign(['corrector_id']);
            $table->dropForeign(['redaction_id']);
        });
        Schema::dropIfExists('corrector_redaction');
        Schema::dropIfExists('redactions');
    }
}
