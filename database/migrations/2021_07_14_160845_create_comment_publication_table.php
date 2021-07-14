<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentPublicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comment_publication', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('comment_id')->index('fk_comment_publication_comment_idx');
			$table->integer('publication_id')->index('fk_comment_publication_publication_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comment_publication');
	}

}
