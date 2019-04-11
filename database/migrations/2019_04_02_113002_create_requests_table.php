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
            $table->String('remarques', 500);
            $table->integer('requestable_id')->nullable();
            $table->String('requestable_type')->nullable();
            $table->string('livrable')->nullable();
            $table->string('chd')->nullable();
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
