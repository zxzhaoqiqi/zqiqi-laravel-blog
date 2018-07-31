<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PageVeriablesTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(ArticleTagTableSeeder::class);
        $this->call(ArticlesContentsTableSeeder::class);
    }
}
