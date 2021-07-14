<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('likes', function(Blueprint $table)
		{
			$table->foreign('publication_id', 'fk_likes_publication')->references('id')->on('publications')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('user_id', 'fk_likes_user')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('likes', function(Blueprint $table)
		{
			$table->dropForeign('fk_likes_publication');
			$table->dropForeign('fk_likes_user');
		});
	}

}
