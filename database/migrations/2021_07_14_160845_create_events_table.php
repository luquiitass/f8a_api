<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('player_id')->nullable()->index('fk_events_plalyer_idx');
			$table->integer('game_id')->nullable()->index('fk_events_game_idx');
			$table->integer('time')->nullable();
			$table->integer('type_event_id')->nullable()->index('fk_events_type_event_idx');
			$table->integer('team_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
