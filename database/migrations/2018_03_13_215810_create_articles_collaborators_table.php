<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesCollaboratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_collaborators', function (Blueprint $table) {
            $table->integer('asso_id')->unsigned();
            $table->foreign('asso_id')->references('id')->on('assos')->onDelete('cascade'); //TODO a revoir

            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade'); //TODO a revoir

            $table->primary(['asso_id', 'article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_collaborators');
    }
}
