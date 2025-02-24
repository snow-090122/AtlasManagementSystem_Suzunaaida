<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            ['subject' => '国語', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => '数学', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => '英語', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
