<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('text');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // Сколько баллов ответ даёт в каждую сферу
        Schema::create('answer_category', function (Blueprint $table) {
            $table->foreignId('answer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('points')->default(1);
            $table->primary(['answer_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answer_category');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
    }
};
