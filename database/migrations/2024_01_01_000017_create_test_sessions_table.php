<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('token', 64)->unique(); // для незарегистрированных
            $table->timestamp('completed_at')->nullable();

            // Данные анкеты
            $table->string('city')->nullable();
            $table->enum('education', ['9_классов', '11_классов', 'СПО', 'высшее'])->nullable();
            $table->enum('study_pace', ['быстро', 'не_тороплюсь'])->nullable();
            $table->enum('study_form_pref', ['очная', 'заочная', 'любая'])->nullable();

            $table->timestamps();
        });

        Schema::create('test_session_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answer_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['test_session_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_session_answers');
        Schema::dropIfExists('test_sessions');
    }
};
