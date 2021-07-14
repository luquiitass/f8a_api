<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('route', 245);
			$table->string('title', 105)->nullable();
			$table->string('content', 50);
			$table->integer('content_id')->nullable();
			$table->boolean('isShow')->nullable()->default(0);
			$table->integer('user_id');
			$table->timestamps();
			$table->string('type', 45);
			$table->string('autor_table', 100)->nullable();
			$table->integer('autor_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
