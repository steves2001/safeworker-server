<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUservalidateTable extends Migration {

	public function up()
	{
		Schema::create('uservalidate', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('userid')->unsigned();
			$table->string('validationtoken', 60);
			$table->string('temppassword');
		});
	}

	public function down()
	{
		Schema::drop('uservalidate');
	}
}