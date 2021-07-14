<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPassRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pass_requests', function(Blueprint $table)
		{
			$table->foreign('player_id', 'fk_pass_requests_player')->references('id')->on('players')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('team_id', 'fk_pass_requests_team')->references('id')->on('teams')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pass_requests', function(Blueprint $table)
		{
			$table->dropForeign('fk_pass_requests_player');
			$table->dropForeign('fk_pass_requests_team');
		});
	}

}
