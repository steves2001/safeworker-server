<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnnouncementsTable extends Migration {

	public function up()
	{
		Schema::create('announcements', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('source')->default('1');
			$table->string('title', 255)->default('Notification');
			$table->text('content');
			$table->char('visible', 1)->default('Y');
		});
	}

	public function down()
	{
		Schema::drop('announcements');
	}
}