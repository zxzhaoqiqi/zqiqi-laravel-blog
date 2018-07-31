<?php

use Illuminate\Database\Seeder;

class ArticleTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ArticleTag::class, 100)->create();
    }
}
