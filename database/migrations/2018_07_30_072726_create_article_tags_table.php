<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateArticleTagsTable.
 */
class CreateArticleTagsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_tags', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();

            $table->index('article_id');
            $table->index('tag_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('article_tags');
	}
}
