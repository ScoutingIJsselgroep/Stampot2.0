<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('transactions', function (Blueprint $table) {
          $table->increments('id');
          $table->string('description');
          $table->decimal('saldo_before', 8, 2);
          $table->decimal('saldo_after', 8, 2);
          $table->decimal('mutation', 8, 2);
          $table->integer('amount')->nullable();
          $table->integer('user_id')->unsigned();
          $table->integer('product_id')->unsigned();
          $table->timestamp('transaction_created_at')->useCurrent();
      });

      Schema::table('transactions', function (Blueprint $table) {
        $table->foreign('user_id')->references('id')->on('users');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
