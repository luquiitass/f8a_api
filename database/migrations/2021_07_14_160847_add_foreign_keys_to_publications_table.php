<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPublicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('publications', function(Blueprint $table)
		{
			$table->foreign('image_id', 'fk_publications_image')->references('id')->on('images')->onUpdate('SET NULL')->onDelete('SET NULL');
			$table->foreign('user_id', 'fk_publications_users')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('publications', function(Blueprint $table)
		{
			$table->dropForeign('fk_publications_image');
			$table->dropForeign('fk_publications_users');
		});
	}

}
