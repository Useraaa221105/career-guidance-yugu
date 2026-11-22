<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Сферы деятельности (для блока 'sklon')
        $spheres = [
            ['name' => 'IT',                  'slug' => 'it',         'type' => 'sphere', 'description' => 'Информационные технологии, программирование, кибербезопасность', 'color' => '#6366f1', 'icon' => '💻'],
            ['name' => 'Техника',             'slug' => 'tech',       'type' => 'sphere', 'description' => 'Инженерия, строительство, энергетика, нефтегазовое дело',        'color' => '#f59e0b', 'icon' => '⚙️'],
            ['name' => 'Естественные науки',  'slug' => 'science',    'type' => 'sphere', 'description' => 'Химия, экология, геология, биология',                            'color' => '#10b981', 'icon' => '🔬'],
            ['name' => 'Здоровье',            'slug' => 'health',     'type' => 'sphere', 'description' => 'Физическая культура, спорт, здоровый образ жизни',               'color' => '#ef4444', 'icon' => '🏃'],
            ['name' => 'Гуманитарные',        'slug' => 'humanities', 'type' => 'sphere', 'description' => 'Филология, лингвистика, журналистика',                           'color' => '#8b5cf6', 'icon' => '📚'],
            ['name' => 'Социальные',          'slug' => 'social',     'type' => 'sphere', 'description' => 'Социальная работа, туризм, сервис',                              'color' => '#ec4899', 'icon' => '🤝'],
            ['name' => 'Педагогика',          'slug' => 'pedagogy',   'type' => 'sphere', 'description' => 'Образование, воспитание, психология',                            'color' => '#14b8a6', 'icon' => '🎓'],
            ['name' => 'Экономика',           'slug' => 'economics',  'type' => 'sphere', 'description' => 'Экономика, менеджмент, финансы, управление',                     'color' => '#f97316', 'icon' => '📊'],
            ['name' => 'Право',               'slug' => 'law',        'type' => 'sphere', 'description' => 'Юриспруденция, правоведение',                                    'color' => '#64748b', 'icon' => '⚖️'],
        ];

        // Типы интеллекта по Гарднеру (для блока 'intellect')
        $intellectTypes = [
            ['name' => 'Логический',       'slug' => 'int_logical',       'type' => 'intellect', 'description' => 'Склонность к числам, логике и системному мышлению',     'color' => '#3b82f6', 'icon' => '🔢'],
            ['name' => 'Вербальный',        'slug' => 'int_verbal',        'type' => 'intellect', 'description' => 'Лёгкость с языком, словами и коммуникацией',            'color' => '#a855f7', 'icon' => '📝'],
            ['name' => 'Пространственный',  'slug' => 'int_spatial',       'type' => 'intellect', 'description' => 'Мышление образами, схемами, визуализация',              'color' => '#06b6d4', 'icon' => '🗺️'],
            ['name' => 'Музыкальный',       'slug' => 'int_musical',       'type' => 'intellect', 'description' => 'Чувство ритма, мелодии, звуковое восприятие',           'color' => '#f43f5e', 'icon' => '🎵'],
            ['name' => 'Телесный',          'slug' => 'int_bodily',        'type' => 'intellect', 'description' => 'Координация, ловкость, работа руками',                  'color' => '#84cc16', 'icon' => '🤸'],
            ['name' => 'Межличностный',     'slug' => 'int_interpersonal', 'type' => 'intellect', 'description' => 'Понимание людей, эмпатия, лидерство',                  'color' => '#f97316', 'icon' => '👥'],
            ['name' => 'Внутриличностный',  'slug' => 'int_intrapersonal', 'type' => 'intellect', 'description' => 'Самопознание, рефлексия, понимание своих эмоций',       'color' => '#8b5cf6', 'icon' => '🪞'],
            ['name' => 'Натуралистический', 'slug' => 'int_natural',       'type' => 'intellect', 'description' => 'Наблюдение за природой, классификация, экология',       'color' => '#22c55e', 'icon' => '🌿'],
        ];

        // Жизненные ценности (для блока 'values')
        $values = [
            ['name' => 'Достижение',   'slug' => 'val_achievement', 'type' => 'value', 'description' => 'Карьерный рост, результаты и профессиональный успех',         'color' => '#f59e0b', 'icon' => '🏆'],
            ['name' => 'Творчество',   'slug' => 'val_creativity',  'type' => 'value', 'description' => 'Самовыражение, создание нового, оригинальность',               'color' => '#ec4899', 'icon' => '🎨'],
            ['name' => 'Стабильность', 'slug' => 'val_security',    'type' => 'value', 'description' => 'Надёжность, постоянство, защищённость',                        'color' => '#6366f1', 'icon' => '🛡️'],
            ['name' => 'Свобода',      'slug' => 'val_freedom',     'type' => 'value', 'description' => 'Независимость, самостоятельность, гибкий график',              'color' => '#14b8a6', 'icon' => '🦅'],
            ['name' => 'Помощь',       'slug' => 'val_service',     'type' => 'value', 'description' => 'Служение людям, польза обществу, социальная значимость',       'color' => '#ef4444', 'icon' => '❤️'],
            ['name' => 'Благополучие', 'slug' => 'val_wealth',      'type' => 'value', 'description' => 'Материальный достаток, хорошая зарплата',                      'color' => '#10b981', 'icon' => '💰'],
            ['name' => 'Признание',    'slug' => 'val_recognition', 'type' => 'value', 'description' => 'Статус, уважение, известность в своей сфере',                  'color' => '#f97316', 'icon' => '⭐'],
            ['name' => 'Развитие',     'slug' => 'val_growth',      'type' => 'value', 'description' => 'Постоянное обучение, знания, личностный рост',                  'color' => '#8b5cf6', 'icon' => '📈'],
        ];

        foreach (array_merge($spheres, $intellectTypes, $values) as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
