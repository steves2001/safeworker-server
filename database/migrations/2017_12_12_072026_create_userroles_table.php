<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserrolesTable extends Migration {

	public function up()
	{
		Schema::create('userroles', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('userid')->unsigned();
			$table->integer('roleid')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('userroles');
	}
}