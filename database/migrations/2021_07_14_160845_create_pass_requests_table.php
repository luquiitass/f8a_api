<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePassRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pass_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('team_id')->nullable()->index('fk_pass_requests_team_idx');
			$table->integer('player_id')->nullable()->index('fk_pass_requests_player_idx');
			$table->enum('status', array('Pendiente','Aceptado','Rechazado'))->nullable();
			$table->integer('try')->nullable();
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
		Schema::drop('pass_requests');
	}

}
