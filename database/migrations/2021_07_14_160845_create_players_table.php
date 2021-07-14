<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('players', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 100);
			$table->string('nick', 100)->nullable();
			$table->date('birth_date')->nullable();
			$table->integer('number');
			$table->decimal('height', 10)->nullable();
			$table->decimal('weight', 10)->nullable();
			$table->integer('position_id')->index('fk_players_position_idx');
			$table->timestamps();
			$table->integer('photo_id')->nullable()->index('fk_players_Images_idx');
			$table->integer('user_id')->nullable()->index('fk_players_user_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('players');
	}

}
