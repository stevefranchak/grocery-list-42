<?php

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->truncate();
        User::create(array(
            'email' => 'stevefranchak@gmail.com',
            'password' => Hash::make('test')
        ))->save();
    }
}