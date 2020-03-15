<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('credit_id')->nullable();
            $table->tinyInteger('transaction_type');
            $table->tinyInteger('is_manual')->default(\App\Models\CreditTransaction::$isManual);
            $table->tinyInteger('is_hold')->default(\App\Models\CreditTransaction::$isNotHold);
            $table->decimal('amount', 10, 2);
            $table->string('reference');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('credit_transactions');
    }
}
