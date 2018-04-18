<?php

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->truncate();
        User::create(array(
            'email' => 'stevefranchak@yahoo.com',
            'password' => Hash::make('test')
        ))->save();
    }
}