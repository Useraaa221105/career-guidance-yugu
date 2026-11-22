<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Исправляем исторические записи: если is_active=0 или NULL — включаем.
        // Это идемпотентно: записи с is_active=1 не затрагиваются.
        DB::table('questions')
            ->where(fn($q) => $q->where('is_active', false)->orWhereNull('is_active'))
            ->update(['is_active' => true]);

        DB::table('answers')
            ->where(fn($q) => $q->where('is_active', false)->orWhereNull('is_active'))
            ->update(['is_active' => true]);
    }

    public function down(): void
    {
        // Откат нецелесообразен: нельзя отличить «намеренно отключённые»
        // от «включённых этой миграцией».
    }
};
