<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->
            insert([
                'name' => 'jake',
                'email' => 'jake@jmctyre.com',
                'password' => bcrypt('jake')
            ]);

        DB::table('users')->
            insert([
                'name' => 'tester',
                'email' => 'jake+tester@jmctyre.com',
                'password' => bcrypt('tester'),
                'api_secret' => 'secret'
            ]);
    }
}
