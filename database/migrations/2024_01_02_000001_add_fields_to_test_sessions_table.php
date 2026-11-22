<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->string('name')->nullable()->after('study_form_pref');
            $table->unsignedTinyInteger('age')->nullable()->after('name');
            $table->enum('gender', ['мужской', 'женский', 'не_указывать'])->nullable()->after('age');
            $table->enum('institution_type', ['школа', 'колледж', 'университет', 'не_обучаюсь'])->nullable()->after('gender');
            $table->enum('budget', ['бюджет', 'платно'])->nullable()->after('institution_type');
            $table->enum('relocation', ['да', 'возможно', 'нет'])->nullable()->after('budget');
            $table->enum('dormitory', ['да', 'возможно', 'нет'])->nullable()->after('relocation');
            $table->enum('ege_status', ['определился', 'выбираю', 'примерно', 'не_определился', 'уже_сдавал'])->nullable()->after('dormitory');
        });
    }

    public function down(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->dropColumn(['name', 'age', 'gender', 'institution_type', 'budget', 'relocation', 'dormitory', 'ege_status']);
        });
    }
};
