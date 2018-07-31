<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePageVariablesTable.
 */
class CreatePageVariablesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page_variables', function(Blueprint $table) {
            $table->increments('id');
            $table->string('meta_title',255);
            $table->text('meta_keyword');
            $table->text('meta_description');
            $table->unsignedTinyInteger('type')->default(0)->comment('0为网站默认 1为页面');
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
		Schema::drop('page_variables');
	}
}
