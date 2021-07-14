<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('location', 245)->nullable();
			$table->time('time')->nullable();
			$table->text('description')->nullable();
			$table->enum('status', array('Pendiente','Suspendido','Jugado'))->nullable();
			$table->integer('l_team')->nullable();
			$table->integer('v_team')->nullable()->index('fk_games_team2_idx');
			$table->integer('l_goals')->nullable()->default(0);
			$table->integer('v_goals')->nullable()->default(0);
			$table->timestamps();
			$table->date('date')->nullable();
			$table->index(['l_team','v_team'], 'index2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('games');
	}

}
