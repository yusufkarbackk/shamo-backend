<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('users_id');
            $table->bigInteger('products_id');
            $table->bigInteger('transactions_id');
            $table->bigInteger('quantity');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_items');
    }
}