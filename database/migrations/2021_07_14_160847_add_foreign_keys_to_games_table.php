<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('games', function(Blueprint $table)
		{
			$table->foreign('l_team', 'fk_games_team1')->references('id')->on('teams')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('v_team', 'fk_games_team2')->references('id')->on('teams')->onUpdate('CASCADE')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('games', function(Blueprint $table)
		{
			$table->dropForeign('fk_games_team1');
			$table->dropForeign('fk_games_team2');
		});
	}

}
