<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
			$table->integer('asso_id')->unsigned()->nullable();
			$table->foreign('asso_id')->references('id')->on('assos');
			$table->integer('user_id')->unsigned()->nullable()->change();
			$table->foreign('user_id')->references('id')->on('users');
            $table->text('scopes')->nullable(); // Scopes définis pour le client credential
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
