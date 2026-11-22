<div align="center">

  <img src="public/images/logo-yugu.jpg" alt="Логотип ЮГУ" width="110" />

  <h1>🎓 Карьерная траектория ЮГУ</h1>

  <p>
    <strong>Найди профессию, которая подходит именно тебе.</strong><br/>
    Веб-сервис профориентационной диагностики и построения образовательного маршрута в Югорском государственном университете и его филиалах.
  </p>

  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite" />
    <img src="https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite" />
  </p>

</div>

---

## 📊 Проект в цифрах

| 📚 Программы | 🏛 Учреждения ХМАО | 💼 Профессии | ❓ Вопросы / Ответы | 🧠 Категории | 🗺️ Маршруты |
| :---: | :---: | :---: | :---: | :---: | :---: |
| **105** | **6** | **185** | **35 / 176** | **25** (9 сфер) | **72** |

---

## ⚡ Как это работает

```mermaid
graph LR
    A[📝 Анкета + 3 блока] --> B[⚙️ ScoringService]
    B --> C[🎯 Топ-сферы интересов]
    C --> D[🎓 Подбор программ ЮГУ]
    D --> E[🗺️ Карта карьерных путей]
```

1. **Диагностика (~35 минут):** пользователь заполняет анкету и проходит 3 тестовых блока — *«Склонности в деятельности»*, *«Тип интеллекта» (по Гарднеру)* и *«Жизненные ценности»*.
2. **Умный расчёт (`ScoringService`):** каждый ответ начисляет баллы по категориям, система определяет ведущие сферы интересов и сопоставляет их с уровнем образования и параметрами анкеты.
3. **Карта карьерных путей:** пользователь получает готовый маршрут — **стартовая позиция → подходящая программа → следующий шаг (например, магистратура) → конкретные профессии**.

---

## ✨ Возможности сервиса

### 👤 Для абитуриента и студента
* **Личный кабинет и прогресс:** сохранение сессий тестирования, возможность вернуться к блокам и пересчитать результаты после обновления анкеты.
* **Наглядные результаты:** рейтинг ключевых сфер интересов и визуальная карта образовательных траекторий.
* **База ЮГУ и филиалов:** программы головного вуза (Ханты-Мансийск), Многопрофильного колледжа и филиалов (Сургут, Нефтеюганск, Нижневартовск, Лянтор).

### 🛠 Панель администратора
* **Полный CRUD без правки кода:** управление вопросами, вариантами ответов, баллами по категориям, программами, профессиями и учреждениями.
* **Аналитика:** просмотр прохождений диагностики и результатов пользователей.
* **Безопасное удаление:** проверка зависимостей перед удалением связанных сущностей, чтобы не нарушить работу алгоритма рекомендаций.

---

## 🚀 Быстрый старт

```bash
# 1. Клонируйте репозиторий
git clone [https://github.com/Useraaa221105/career-guidance-yugu.git](https://github.com/Useraaa221105/career-guidance-yugu.git)
cd career-guidance-yugu

# 2. Установите зависимости
composer install
npm install

# 3. Настройте окружение и ключ приложения
cp .env.example .env
php artisan key:generate

# 4. Создайте БД и заполните справочники
php artisan migrate --seed

# 5. Соберите фронтенд и запустите сервер
npm run build
php artisan serve
```

После запуска сервис откроется по адресу `http://127.0.0.1:8000`.

---

## 📁 Архитектура проекта

<details>
<summary><b>Развернуть структуру папок</b></summary>

* `app/Http/Controllers/` — контроллеры тестирования (`TestController`), админ-панели (`AdminController`), профиля и авторизации (`Laravel Breeze`).
* `app/Services/ScoringService.php` — ядро алгоритма: подсчёт баллов, топ-сферы, ранжирование программ и сборка карьерной карты.
* `app/Models/` — модели БД (`Question`, `Answer`, `TestBlock`, `TestSession`, `Category`, `Program`, `Profession`, `Institution`, `School`).
* `database/seeders/` — сидеры для наполнения базы вопросами, программами и описаниями.
* `info_edu/` и `docs/` — исходные данные по программам ЮГУ и спецификация (`master-data-spec.md`).

</details>

---

## 👨‍💻 Автор

**akhmatkulov_005** — Инженерная школа цифровых технологий ЮГУ

<p>
  <a href="https://github.com/Useraaa221105">
    <img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" alt="GitHub" />
  </a>
  <a href="https://t.me/akhmatkulov_005">
    <img src="https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white" alt="Telegram" />
  </a>
  <a href="https://vk.ru/akhmatkulov_005">
    <img src="https://img.shields.io/badge/VK-0077FF?style=for-the-badge&logo=vk&logoColor=white" alt="VK" />
  </a>
</p>
