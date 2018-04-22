<?php

class UserTableSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->truncate();

        User::create(array(
            'email' => 'stevefranchak@gmail.com',
            'password' => Hash::make('reach4TEHsky'),
            'accountId' => '5d67531e-a672-4c08-a9a4-870b6df6ee32',
        ));

        User::create(array(
            'email' => '64lengthpassword@omg.wow',
            'password' => Hash::make('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'), // 64 a's
            'accountId' => 'ffa91d3d-a233-4b3d-bd17-9d197b420e64',
        ));
    }
}