<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRedesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('redes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->enum('name', array('Facebook','Instagram','WhatsApp','Twitter'));
			$table->string('url', 245);
			$table->string('name_model', 45);
			$table->integer('model_id');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('redes');
	}

}
