<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayerTeamTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('player_team', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('player_id')->index('fk_player_team_player_idx');
			$table->integer('team_id')->index('fk_player_team_team_idx');
			$table->boolean('current')->default(1);
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
		Schema::drop('player_team');
	}

}
