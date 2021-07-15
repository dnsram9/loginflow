<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*app('db')
            ->table('users')
            ->insert([
                'first_name' => 'test_first1',
                'last_name' => 'test_last1',
                'email' => 'testuser1@gmail.com',
                'password' => 'test123'
            ]);*/ 
        
            app('db')
            ->table('users')
            ->insert([
                'first_name' => 'test_first2',
                'last_name' => 'test_last2',
                'email' => 'testuser2@gmail.com',
                'password' => 'test12345'
            ]);
    }
}
