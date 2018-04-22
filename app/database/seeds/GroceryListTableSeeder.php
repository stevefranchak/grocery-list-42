<?php

class GroceryListTableSeeder extends Seeder {
    public function run()
    {
        DbTableHelpers::emptyTable('grocery_lists');

        $userId = User::where('email', '=', 'stevefranchak@gmail.com')->first()->id;

        GroceryList::create(array(
            'user_id' => $userId,
            'name' => 'Weekly Grocery List',
            'for_store' => 'Wegmans',
        ));

        GroceryList::create(array(
            'user_id' => $userId,
            'name' => 'Target Run',
        ));
    }
}