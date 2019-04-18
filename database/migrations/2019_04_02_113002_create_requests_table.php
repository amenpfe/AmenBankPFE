<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    	Schema::create('requests', function(Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('status');
            $table->longText('remarques');
            $table->integer('requestable_id')->nullable();
            $table->string('requestable_type')->nullable();
            $table->string('user_doc')->nullable();
            $table->string('ced_doc')->nullable();
            $table->string('organisation_doc')->nullable();
            $table->string('chd_doc')->nullable();
            $table->string('analyse_doc')->nullable();
            $table->string('conception_doc')->nullable();
            $table->string('logiciel_doc')->nullable();
            $table->string('test_doc')->nullable();
            $table->string('recette_doc')->nullable();
            $table->string('circulaire_doc')->nullable();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('restrict')
                  ->onUpdate('restrict');
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
        Schema::table('requests', function(Blueprint $table) {
			$table->dropForeign('requests_user_id_foreign');
		});
		Schema::drop('requests');
    }
}
