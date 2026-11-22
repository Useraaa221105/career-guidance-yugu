<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite не поддерживает ALTER COLUMN — пересоздаём таблицу.
        // Добавляем 'очно-заочная' в CHECK constraint study_form_pref.
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("
            CREATE TABLE \"test_sessions_new\" (
                \"id\" integer primary key autoincrement not null,
                \"user_id\" integer,
                \"token\" varchar not null,
                \"completed_at\" datetime,
                \"city\" varchar,
                \"education\" varchar check (\"education\" in ('9_классов', '11_классов', 'СПО', 'высшее')),
                \"study_pace\" varchar check (\"study_pace\" in ('быстро', 'не_тороплюсь')),
                \"study_form_pref\" varchar check (\"study_form_pref\" in ('очная', 'заочная', 'очно-заочная', 'любая')),
                \"created_at\" datetime,
                \"updated_at\" datetime,
                \"name\" varchar,
                \"age\" integer,
                \"gender\" varchar check (\"gender\" in ('мужской', 'женский', 'не_указывать')),
                \"institution_type\" varchar check (\"institution_type\" in ('школа', 'колледж', 'университет', 'не_обучаюсь')),
                \"budget\" varchar check (\"budget\" in ('бюджет', 'платно')),
                \"relocation\" varchar check (\"relocation\" in ('да', 'возможно', 'нет')),
                \"dormitory\" varchar check (\"dormitory\" in ('да', 'возможно', 'нет')),
                \"ege_status\" varchar check (\"ege_status\" in ('определился', 'выбираю', 'примерно', 'не_определился', 'уже_сдавал')),
                foreign key(\"user_id\") references \"users\"(\"id\") on delete set null
            )
        ");

        DB::statement('INSERT INTO "test_sessions_new" SELECT * FROM "test_sessions"');

        Schema::drop('test_sessions');

        DB::statement('ALTER TABLE "test_sessions_new" RENAME TO "test_sessions"');

        DB::statement('CREATE UNIQUE INDEX "test_sessions_token_unique" ON "test_sessions" ("token")');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // Откат: убираем 'очно-заочная' из CHECK constraint.
        // Строки с этим значением не могут быть сохранены в старой схеме — пропускаем их.
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("
            CREATE TABLE \"test_sessions_old\" (
                \"id\" integer primary key autoincrement not null,
                \"user_id\" integer,
                \"token\" varchar not null,
                \"completed_at\" datetime,
                \"city\" varchar,
                \"education\" varchar check (\"education\" in ('9_классов', '11_классов', 'СПО', 'высшее')),
                \"study_pace\" varchar check (\"study_pace\" in ('быстро', 'не_тороплюсь')),
                \"study_form_pref\" varchar check (\"study_form_pref\" in ('очная', 'заочная', 'любая')),
                \"created_at\" datetime,
                \"updated_at\" datetime,
                \"name\" varchar,
                \"age\" integer,
                \"gender\" varchar check (\"gender\" in ('мужской', 'женский', 'не_указывать')),
                \"institution_type\" varchar check (\"institution_type\" in ('школа', 'колледж', 'университет', 'не_обучаюсь')),
                \"budget\" varchar check (\"budget\" in ('бюджет', 'платно')),
                \"relocation\" varchar check (\"relocation\" in ('да', 'возможно', 'нет')),
                \"dormitory\" varchar check (\"dormitory\" in ('да', 'возможно', 'нет')),
                \"ege_status\" varchar check (\"ege_status\" in ('определился', 'выбираю', 'примерно', 'не_определился', 'уже_сдавал')),
                foreign key(\"user_id\") references \"users\"(\"id\") on delete set null
            )
        ");

        DB::statement("
            INSERT INTO \"test_sessions_old\"
            SELECT * FROM \"test_sessions\"
            WHERE \"study_form_pref\" IS NULL OR \"study_form_pref\" != 'очно-заочная'
        ");

        Schema::drop('test_sessions');

        DB::statement('ALTER TABLE "test_sessions_old" RENAME TO "test_sessions"');

        DB::statement('CREATE UNIQUE INDEX "test_sessions_token_unique" ON "test_sessions" ("token")');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
