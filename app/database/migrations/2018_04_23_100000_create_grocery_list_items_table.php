<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroceryListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grocery_list_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('grocery_list_id');
            $table->foreign('grocery_list_id')
                ->references('id')->on('grocery_lists')
                ->onDelete('cascade');
            $table->string('name');
            $table->boolean('checked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grocery_list_items');
    }
}
