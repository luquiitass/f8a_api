<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->foreign('game_id', 'fk_events_game')->references('id')->on('games')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('player_id', 'fk_events_plalyer')->references('id')->on('players')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('type_event_id', 'fk_events_type_event')->references('id')->on('types_events')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->dropForeign('fk_events_game');
			$table->dropForeign('fk_events_plalyer');
			$table->dropForeign('fk_events_type_event');
		});
	}

}
