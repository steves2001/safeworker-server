<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('uservalidate', function(Blueprint $table) {
			$table->foreign('userid')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('uservalidate', function(Blueprint $table) {
			$table->dropForeign('uservalidate_userid_foreign');
		});
	}
}