<?php

use Illuminate\Database\Seeder;

class PageVeriablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\PageVariables::class, 10)->create();
    }
}
