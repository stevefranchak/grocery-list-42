<?php

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->truncate();
        User::create(array(
            'email' => 'stevefranchak@yahoo.com',
            'name' => 'Steve Franchak',
            'password' => Hash::make('test')
        ))->save();
    }
}