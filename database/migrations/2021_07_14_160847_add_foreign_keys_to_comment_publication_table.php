<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentPublicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comment_publication', function(Blueprint $table)
		{
			$table->foreign('comment_id', 'fk_comment_publication_comment')->references('id')->on('comments')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('publication_id', 'fk_comment_publication_publication')->references('id')->on('publications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comment_publication', function(Blueprint $table)
		{
			$table->dropForeign('fk_comment_publication_comment');
			$table->dropForeign('fk_comment_publication_publication');
		});
	}

}
