<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_results', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Store the student's name
            $table->string('exam_id'); // Store the exam ID
            $table->integer('total_questions'); // Total questions in the exam
            $table->integer('total_attempts'); // Total attempts made by the student
            $table->integer('total_correct'); // Total correct answers
            $table->decimal('percentage', 5, 2); // Percentage scored by the student
            $table->integer('correct_count')->default(0); // Number of correct answers
            $table->integer('incorrect_count')->default(0); // Number of incorrect answers
            $table->integer('unsolved_count')->default(0); // Number of unanswered questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_results');
    }
};
