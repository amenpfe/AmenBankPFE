<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptimizationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optimizationRequests', function(Blueprint $table) {
			$table->increments('id');
            $table->integer('type');
			$table->integer('project_id')->unsigned();
			$table->foreign('project_id')
				  ->references('id')
				  ->on('projects')
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
        Schema::table('optimizationRequests', function(Blueprint $table) {
			$table->dropForeign('optimizationRequests_project_id_foreign');
		});
		Schema::drop('optimizationRequest');
    }
}
