<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\TestBlock;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            ['slug' => 'anketa',    'name' => 'Анкета',                      'description' => 'Расскажи о себе — поможет точнее подобрать программы',    'icon' => '📋', 'estimated_minutes' => 5,  'order_index' => 1],
            ['slug' => 'sklon',     'name' => 'Склонности в деятельности',   'description' => 'Определим твои профессиональные интересы и склонности',    'icon' => '🎯', 'estimated_minutes' => 10, 'order_index' => 2],
            ['slug' => 'intellect', 'name' => 'Тип интеллекта',              'description' => 'Выявим твой доминирующий тип мышления по Гарднеру',        'icon' => '🧠', 'estimated_minutes' => 10, 'order_index' => 3],
            ['slug' => 'values',    'name' => 'Жизненные ценности',          'description' => 'Узнаем, что для тебя важно в будущей профессии и жизни',   'icon' => '💎', 'estimated_minutes' => 10, 'order_index' => 4],
        ];

        foreach ($blocks as $blockData) {
            TestBlock::firstOrCreate(['slug' => $blockData['slug']], $blockData);
        }

        // Назначаем все существующие вопросы без block_id → блоку 'sklon'
        $sklonBlock = TestBlock::where('slug', 'sklon')->first();
        if ($sklonBlock) {
            Question::whereNull('block_id')->update(['block_id' => $sklonBlock->id]);
        }
    }
}
