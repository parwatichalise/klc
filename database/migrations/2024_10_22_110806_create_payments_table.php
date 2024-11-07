<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger for user_id
            $table->string('product_id');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable(); // For storing eSewa transaction ID
            $table->string('status')->default('pending'); // Payment status
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }   

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
