<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('announcements', function(Blueprint $table) {
			$table->foreign('source')->references('id')->on('sources')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('userroles', function(Blueprint $table) {
			$table->foreign('userid')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('userroles', function(Blueprint $table) {
			$table->foreign('roleid')->references('id')->on('roles')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('announcements', function(Blueprint $table) {
			$table->dropForeign('announcements_source_foreign');
		});
		Schema::table('userroles', function(Blueprint $table) {
			$table->dropForeign('userroles_userid_foreign');
		});
		Schema::table('userroles', function(Blueprint $table) {
			$table->dropForeign('userroles_roleid_foreign');
		});
	}
}