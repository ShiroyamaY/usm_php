# Отчет по лабораторной работе №4: Обработка и валидация форм

## Введение

В рамках данной лабораторной работы был разработан проект "Каталог рецептов", реализующий функциональность добавления, валидации и отображения рецептов. Проект позволяет создавать новые рецепты с различными параметрами, сохранять их в текстовом файле и просматривать сохраненные рецепты.

## Задание 1: Создание проекта

### Структура проекта

Была создана следующая файловая структура проекта:

```
recipe-book/
├── public/                        
│   ├── index.php                   # Главная страница (вывод последних рецептов)
│   └── recipe/                    
│       ├── create.php              # Форма добавления рецепта
│       └── index.php               # Страница с отображением рецептов
├── src/                            
│   ├── handlers/                   # Обработчики форм
│   │   └── recipe_handler.php      # Обработчик формы добавления рецепта
│   └── helpers.php                 # Вспомогательные функции для обработки данных
├── storage/                        
│   └── recipes.txt                 # Файл для хранения рецептов
└── README.md                       # Описание проекта
```

# Примечание: описание выполнения будет ввиде комментариев в самом коде
## Задание 2: Создание формы добавления рецепта

### Форма добавления рецепта (public/recipe/create.php)

Была создана HTML-форма для добавления рецепта со следующими полями:
- Название рецепта (текстовое поле)
- Категория рецепта (выпадающий список)
- Ингредиенты (текстовая область)
- Описание рецепта (текстовая область)
- Теги (множественный выбор)
- Шаги приготовления (текстовая область)

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Recipe</title>
    <style>
        <!-- Тут типа стили -->
    </style>
</head>
<body>
    <h1>Add New Recipe</h1>
    
    <form action="../../src/handlers/recipe_handler.php" method="POST">
        <div class="form-group">
            <label for="title">Recipe Title:</label>
            <input type="text" id="title" name="title" required>
            <?php if (isset($errors['title'])): ?>
                <div class="error"><?php echo $errors['title']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="appetizer">Appetizer</option>
                <option value="main_course">Main Course</option>
                <option value="dessert">Dessert</option>
                <option value="soup">Soup</option>
                <option value="salad">Salad</option>
                <option value="drink">Drink</option>
            </select>
            <?php if (isset($errors['category'])): ?>
                <div class="error"><?php echo $errors['category']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="5" required></textarea>
            <?php if (isset($errors['ingredients'])): ?>
                <div class="error"><?php echo $errors['ingredients']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="error"><?php echo $errors['description']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="tags">Tags:</label>
            <select id="tags" name="tags[]" multiple required>
                <option value="quick">Quick</option>
                <option value="easy">Easy</option>
                <option value="healthy">Healthy</option>
                <option value="vegetarian">Vegetarian</option>
                <option value="vegan">Vegan</option>
                <option value="gluten_free">Gluten Free</option>
                <option value="low_carb">Low Carb</option>
            </select>
            <?php if (isset($errors['tags'])): ?>
                <div class="error"><?php echo $errors['tags']; ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="steps">Preparation Steps:</label>
            <textarea id="steps" name="steps" rows="5" required placeholder="Enter each step on a new line"></textarea>
            <?php if (isset($errors['steps'])): ?>
                <div class="error"><?php echo $errors['steps']; ?></div>
            <?php endif; ?>
        </div>
        
        <button type="submit">Add Recipe</button>
    </form>
</body>
</html>
```

## Задание 3: Обработка формы

### Вспомогательные функции (src/helpers.php)

Были созданы вспомогательные функции для обработки данных:

```php
/**
 * Sanitizes and filters input data
 *
 * @param string $data The data to be sanitized
 * @return string Sanitized data
 */
function sanitizeInput(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validates recipe title
 *
 * @param string $title The recipe title to validate
 * @return array Array with validation status and error message if any
 */
function validateTitle(string $title): array
{
    if (empty($title)) {
        return ['valid' => false, 'message' => 'Recipe title is required'];
    }

    if (strlen($title) < 3) {
        return ['valid' => false, 'message' => 'Recipe title must be at least 3 characters long'];
    }

    if (strlen($title) > 100) {
        return ['valid' => false, 'message' => 'Recipe title must be less than 100 characters'];
    }

    return ['valid' => true];
}

/**
 * Validates recipe category
 *
 * @param string $category The recipe category to validate
 * @return array Array with validation status and error message if any
 */
function validateCategory(string $category): array
{
    $validCategories = [
        'appetizer', 'main_course', 'dessert', 'beverage',
        'soup', 'salad', 'breakfast', 'side_dish'
    ];

    if (empty($category) || !in_array($category, $validCategories)) {
        return ['valid' => false, 'message' => 'Please select a valid category'];
    }

    return ['valid' => true];
}

/**
 * Validates recipe ingredients
 *
 * @param string $ingredients The recipe ingredients to validate
 * @return array Array with validation status and error message if any
 */
function validateIngredients(string $ingredients): array
{
    if (empty($ingredients)) {
        return ['valid' => false, 'message' => 'Ingredients are required'];
    }

    $ingredientLines = explode("\n", $ingredients);
    if (count($ingredientLines) < 2) {
        return ['valid' => false, 'message' => 'Please add at least 2 ingredients'];
    }

    return ['valid' => true];
}

/**
 * Validates recipe description
 *
 * @param string $description The recipe description to validate
 * @return array Array with validation status and error message if any
 */
function validateDescription(string $description): array
{
    if (empty($description)) {
        return ['valid' => false, 'message' => 'Recipe description is required'];
    }

    if (strlen($description) < 10) {
        return ['valid' => false, 'message' => 'Description must be at least 10 characters long'];
    }

    return ['valid' => true];
}

/**
 * Validates recipe tags
 *
 * @param array|null $tags The recipe tags to validate
 * @return array Array with validation status and error message if any
 */
function validateTags(?array $tags): array
{
    $validTags = [
        'vegetarian', 'vegan', 'gluten_free', 'dairy_free',
        'low_carb', 'high_protein', 'quick', 'spicy', 'seasonal'
    ];

    if (empty($tags) || !is_array($tags)) {
        return ['valid' => false, 'message' => 'Please select at least one tag'];
    }

    foreach ($tags as $tag) {
        if (!in_array($tag, $validTags)) {
            return ['valid' => false, 'message' => 'One or more selected tags are invalid'];
        }
    }

    return ['valid' => true];
}

/**
 * Validates recipe preparation steps
 *
 * @param array|string $steps The recipe preparation steps to validate
 * @return array Array with validation status and error message if any
 */
function validateSteps($steps): array
{
    if (is_array($steps)) {
        if (empty($steps) || count($steps) < 1) {
            return ['valid' => false, 'message' => 'Preparation steps are required'];
        }

        $nonEmptySteps = array_filter($steps, function($step) {
            return !empty(trim($step));
        });

        if (count($nonEmptySteps) < 1) {
            return ['valid' => false, 'message' => 'Please add at least 1 preparation step'];
        }
    } else {
        return ['valid' => false, 'message' => 'Invalid steps format'];
    }

    return ['valid' => true];
}

/**
 * Reads all recipes from the storage file
 *
 * @param string $filename Path to the recipes storage file
 * @return array Array of recipe objects
 */
function readRecipes(string $filename = __DIR__ . '/../storage/recipes.txt'): array
{
    if (!file_exists($filename)) {
        return [];
    }

    $recipes = file($filename, FILE_IGNORE_NEW_LINES);
    if (empty($recipes)) {
        return [];
    }

    return array_map('json_decode', $recipes);
}

/**
 * Saves a recipe to the storage file
 *
 * @param array $recipeData Recipe data to save
 * @param string $filename Path to the recipes storage file
 * @return bool True if saving was successful, false otherwise
 */
function saveRecipe(array $recipeData, string $filename = __DIR__ . '/../storage/recipes.txt'): bool
{
    try {
        // Добавляю таймстамп даты создания
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // ковертирую шаги из строк в массив
        if (isset($data['steps']) && is_string($data['steps'])) {
            $data['steps'] = array_filter(explode("\n", $data['steps']));
        }
        
        // ну и добавляю в файл
        file_put_contents($filename, README.mdjson_encode($data) . PHP_EOL, FILE_APPEND);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Gets form validation errors from session
 *
 * @return array Array of validation errors
 */
function getFormErrors(): array
{
    session_start();
    $errors = $_SESSION['form_errors'] ?? [];

    // Чищу ошибки после чтения так как это флеш сообщения
    if (isset($_SESSION['form_errors'])) {
        unset($_SESSION['form_errors']);
    }

    return $errors;
}

/**
 * Sets form validation errors in session
 *
 * @param array $errors Array of validation errors
 * @return void
 */
function setFormErrors(array $errors) {
    session_start();
    $_SESSION['form_errors'] = $errors;
}

/**
 * Format a recipe's creation date
 *
 * @param string $dateStr Date string in ISO format
 * @return string Formatted date
 * @throws DateMalformedStringException
 */
function formatDate(string $dateStr): string
{
    $date = new DateTime($dateStr);
    return $date->format('F j, Y');
}

/**
 * Get pagination data for recipes
 *
 * @param array $recipes Array of all recipes
 * @param int $page Current page number
 * @param int $perPage Number of recipes per page
 * @return array Array with pagination data and page recipes
 */
function getPaginatedRecipes(array $recipes, int $page = 1, int $perPage = 5): array
{
    $totalRecipes = count($recipes);
    $totalPages = ceil($totalRecipes / $perPage);

    $page = max(1, min($page, $totalPages));

    $offset = ($page - 1) * $perPage;
    $pageRecipes = array_slice($recipes, $offset, $perPage);

    return [
        'recipes' => $pageRecipes,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'hasPrevPage' => $page > 1,
        'hasNextPage' => $page < $totalPages
    ];
}
```

### Обработчик формы (src/handlers/recipe_handler.php)

Был создан обработчик формы для валидации и сохранения данных:

```php
<?php
/**
 * Recipe form handler
 * 
 * Processes recipe form data, validates it, and saves to storage
 */

// Включение хелпер функций
require_once __DIR__ . '/../helpers.php';

// Путь к файлу с рецептами
$storageFile = __DIR__ . '/../../storage/recipes.txt';

// Проверяю что данные были отправленны с пост методом
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем входящие данные
    $formData = sanitizeInput($_POST);
    
    // Проводим необходимую валидацию
    $errors = validateRecipe($formData);
    
    if (empty($errors)) {
        // Сохраняю рецепт в файл
        if (saveRecipe($formData, $storageFile)) {
            // редирект в корневую директорию
            header('Location: /public/index.php');
            exit;
        } else {
            $errors['general'] = 'Failed to save recipe. Please try again.';
        }
    }
    
    // если валидация не прошла записываю в сессию "флеш" сообщения с ошибками
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $formData;
    header('Location: /public/recipe/create.php');
    exit;
}

// If not a POST request, redirect to the form
header('Location: /public/recipe/create.php');
exit;
```

## Задание 4: Отображение рецептов

### Главная страница с последними рецептами (public/index.php)

Была создана главная страница для отображения двух последних рецептов:

```php
<?php
/**
 * Homepage - displays the latest recipes
 */

// подключаем хелпер функции
require_once __DIR__ . '/../src/helpers.php';

// Указываю путь к файлу с рецептами
$storageFile = __DIR__ . '/../storage/recipes.txt';

// Получаю последние n рецептов  в данном случае 2
$latestRecipes = getRecipes($storageFile, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book - Home</title>
    <style>
        <!-- тут типа стили -->
    </style>
</head>
<body>
    <h1>Recipe Book</h1>

    <div class="nav">
        <a href="/public/">Home</a>
        <a href="/public/recipe/">All Recipes</a>
        <a href="/public/recipe/create.php">Add New Recipe</a>
    </div>

    <h2>Latest Recipes</h2>
    
    <?php if (empty($latestRecipes)): ?>
        <p>No recipes yet. <a href="/public/recipe/create.php">Add your first recipe!</a></p>
    <?php else: ?>
        <?php foreach ($latestRecipes as $recipe): ?>
            <div class="recipe">
                <h2><?php echo $recipe->title; ?></h2>
                <div class="recipe-meta">
                    Category: <?php echo ucfirst(str_replace('_', ' ', $recipe->category)); ?> | 
                    Added: <?php echo date('F j, Y', strtotime($recipe->created_at)); ?>
                </div>
                <p><?php echo $recipe->description; ?></p>
                <div class="recipe-tags">
                    <?php foreach ($recipe->tags as $tag): ?>
                        <span class="tag"><?php echo ucfirst(str_replace('_', ' ', $tag)); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <p><a href="/public/recipe/">View all recipes</a></p>
    <?php endif; ?>
</body>
</html>
```

### Страница со всеми рецептами и пагинацией (public/recipe/index.php)

Была создана страница для отображения всех рецептов с пагинацией:

```php
<?php
/**
 * All recipes page with pagination
 */

// в миллиардный раз подключаю хелперов
require_once __DIR__ . '/../../src/helpers.php';

// в infinity раз подключаю файл с рецептами
$storageFile = __DIR__ . '/../../storage/recipes.txt';

// Устанавливаю настройки пагинации, кол-во рецептов, текущая страница и офсетик
$recipesPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $recipesPerPage;

// получаю соответствующие пагинации рецепты
$allRecipes = getRecipes($storageFile, $recipesPerPage, $offset);

// Получаю каунт всех рецептов 
$totalRecipes = count(file($storageFile, FILE_IGNORE_NEW_LINES));
$totalPages = ceil($totalRecipes / $recipesPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book - All Recipes</title>
    <style>
        <!-- тут типа стили 3 -->
    </style>
</head>
<body>
    <h1>All Recipes</h1>

    <div class="nav">
        <a href="/">Home</a>
        <a href="/recipe/">All Recipes</a>
        <a href="/recipe/create.php">Add New Recipe</a>
    </div>
    
    <?php if (empty($allRecipes)): ?>
        <p>No recipes yet. <a href="/recipe/create.php">Add your first recipe!</a></p>
    <?php else: ?>
        <?php foreach ($allRecipes as $recipe): ?>
            <div class="recipe">
                <h2><?php echo $recipe->title; ?></h2>
                <div class="recipe-meta">
                    Category: <?php echo ucfirst(str_replace('_', ' ', $recipe->category)); ?> | 
                    Added: <?php echo date('F j, Y', strtotime($recipe->created_at)); ?>
                </div>
                <p><?php echo $recipe->description; ?></p>
                
                <h3>Ingredients:</h3>
                <p><?php echo nl2br($recipe->ingredients); ?></p>
                
                <h3>Steps:</h3>
                <ol>
                    <?php foreach ($recipe->steps as $step): ?>
                        <li><?php echo $step; ?></li>
                    <?php endforeach; ?>
                </ol>
                
                <div class="recipe-tags">
                    <?php foreach ($recipe->tags as $tag): ?>
                        <span class="tag"><?php echo ucfirst(str_replace('_', ' ', $tag)); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
```

## При отображении списка рецептов на странице public/recipe/index.php реализована пагинация, которая позволяет разбивать все рецепты на страницы и загружать их частями.

## Алгоритм работы пагинации:
### Определение параметров пагинации

> 1. Количество рецептов на одной странице ($recipesPerPage).
> 2. Текущая страница ($currentPage), получаемая из GET-параметра (?page=1).
> 3. Общее количество рецептов ($totalRecipes), получаемое из хранилища.
> 4. Вычисление количества страниц ($totalPages), делением общего количества рецептов на количество рецептов на странице.

### Загрузка нужной части рецептов

> 1. Определяется индекс начала ($offset = ($currentPage - 1) * $recipesPerPage).
>
> 2. Загружается нужная часть рецептов (array_slice($recipes, $offset, $recipesPerPage)).

### Отображение рецептов

> 1. На текущей странице выводятся только загруженные рецепты.

### Вывод навигации по страницам

> 1. Генерируются ссылки на предыдущую (?page=X-1) и следующую (?page=X+1) страницы.
>
> 2. Если пользователь находится на первой странице, кнопка «Предыдущая» скрывается.
>
> 3. Если на последней — скрывается кнопка «Следующая»

## Запуск проекта

> Для запуска проекта был использован встроенный PHP-сервер:

```
php -S localhost:8000 -t .
```

При запуске сервера таким образом, корневой директорией становится папка `public`, поэтому доступ к ресурсам осуществляется по следующим URL:

- Главная страница: `http://localhost:8000/public/`
- Страница всех рецептов: `http://localhost:8000/public/recipe/`
- Форма добавления рецепта: `http://localhost:8000/public/recipe/create.php`


## Ответы на контрольные вопросы

### 1. Какие методы HTTP применяются для отправки данных формы?

Для отправки данных формы используются следующие HTTP методы:

- **GET** - данные отправляются как часть URL в виде query-параметров, поэтому он имеет ограничения по объему передаваемых данных и не подходит для передачи конфиденциальной информации, так как данные видны в адресной строке браузера.

- **POST** - данные отправляются в теле HTTP-запроса, а не в URL, что более безопасно для передачи конфиденциальной информации и не имеет жестких ограничений по объему передаваемых данных.

В нашем проекте для отправки формы добавления рецепта используется метод POST, так как мы передаем большой объем данных и изменяем состояние на сервере (добавляем новую запись).

### 2. Что такое валидация данных, и чем она отличается от фильтрации?

**Валидация данных** - это процесс проверки данных на соответствие определенным критериям или правилам. Цель валидации - убедиться, что данные корректны с точки зрения бизнес-логики приложения.

**Фильтрация данных** - это процесс очистки и преобразования данных для обеспечения их безопасности и приведения к нужному формату.

Основные отличия:
- **Цель**: Валидация определяет, соответствуют ли данные требованиям, фильтрация очищает данные от нежелательного содержимого.
- **Результат**: Валидация возвращает результат "данные корректны" или "данные некорректны", фильтрация возвращает преобразованные данные.
- **Порядок выполнения**: Обычно сначала выполняется фильтрация (очистка), а затем валидация (проверка).

### 3. Какие функции PHP используются для фильтрации данных?

В PHP для фильтрации данных используются следующие функции:

- **htmlspecialchars()** - преобразует специальные символы в HTML-сущности, защищая от XSS-атак.
- **strip_tags()** - удаляет HTML и PHP теги из строки.
- **trim()** - удаляет пробельные символы в начале и конце строки.
- **stripslashes()** - удаляет экранирующие слеши.
- **filter_var()** и **filter_input()** - фильтруют переменные с помощью указанного фильтра.
- **filter_var_array()** и **filter_input_array()** - фильтруют массивы переменных.

В моем проекте для фильтрации данных используется функция `sanitizeInput()`, которая применяет `htmlspecialchars()` и `trim()` к входным данным.

## Заключение

В ходе выполнения лабораторной работы был разработан проект "Каталог рецептов", реализующий функциональность создания, валидации и отображения рецептов. Были освоены основные принципы обработки и валидации форм в PHP, работы с файловой системой и организации проекта.

