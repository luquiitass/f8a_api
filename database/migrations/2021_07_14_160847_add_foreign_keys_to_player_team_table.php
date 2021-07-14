<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPlayerTeamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('player_team', function(Blueprint $table)
		{
			$table->foreign('player_id', 'fk_player_team_player')->references('id')->on('players')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('team_id', 'fk_player_team_team')->references('id')->on('teams')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('player_team', function(Blueprint $table)
		{
			$table->dropForeign('fk_player_team_player');
			$table->dropForeign('fk_player_team_team');
		});
	}

}
