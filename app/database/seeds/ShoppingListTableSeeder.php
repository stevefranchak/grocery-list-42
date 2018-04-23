<?php

class ShoppingListTableSeeder extends Seeder {
    public function run()
    {
        DbTableHelpers::emptyTable('shopping_lists');

        $userId = User::where('email', '=', 'stevefranchak@gmail.com')->first()->id;

        ShoppingList::create(array(
            'user_id' => $userId,
            'name' => 'Weekly Grocery List',
            'for_store' => 'Wegmans',
        ));

        ShoppingList::create(array(
            'user_id' => $userId,
            'name' => 'Target Run',
        ));
    }
}