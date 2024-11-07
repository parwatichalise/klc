<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('user_answers', function (Blueprint $table) {
        $table->unsignedBigInteger('quiz_id')->after('user_id')->nullable();
        
        // Adding foreign key constraint
        $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
    });
}

    
};
