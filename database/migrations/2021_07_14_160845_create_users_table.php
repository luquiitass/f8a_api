<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('first_name', 45);
			$table->string('last_name', 45);
			$table->string('email', 45)->unique('email_UNIQUE');
			$table->string('remember_token', 450)->nullable();
			$table->string('password', 145);
			$table->enum('role', array('admin','user'));
			$table->integer('photo_id')->nullable()->index('fk_users_image_idx');
			$table->timestamps();
			$table->string('api_token', 100)->unique('api_token_UNIQUE');
			$table->integer('counts_not')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
