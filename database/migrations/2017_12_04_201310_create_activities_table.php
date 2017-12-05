<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivitiesTable extends Migration {

	public function up()
	{
		Schema::create('activities', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->timestamp('starttime')->nullable($value = true);
			$table->timestamp('endtime')->nullable($value = true);
			$table->string('location', 50)->default('');
			$table->char('escortrequired', 1)->default('N');
			$table->string('phone', 11)->default('');
			$table->string('message', 250)->default('');
			$table->char('active', 1)->default('Y');
			$table->char('accepted', 1)->default('N');
			$table->integer('userid')->unsigned();
            //$table->foreign('userid')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('activities');
	}
}