<?php
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
function validateSteps(array|string $steps): array
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
    $directory = dirname($filename);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    return file_put_contents($filename, json_encode($recipeData) . PHP_EOL, FILE_APPEND);
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

    // Clear errors after reading them
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