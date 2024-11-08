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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('heading');
            $table->string('sub_heading')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('time_duration');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
