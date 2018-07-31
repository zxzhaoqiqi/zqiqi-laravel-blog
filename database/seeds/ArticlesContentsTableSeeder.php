<?php

use Illuminate\Database\Seeder;

class ArticlesContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ArticleContent::class, 100)->create();
    }
}
