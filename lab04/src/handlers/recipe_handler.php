<?php
require_once __DIR__ . '/../helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/recipe/create.php');
    exit;
}

$errors = [];

$title = isset($_POST['title']) ? sanitizeInput($_POST['title']) : '';
$titleValidation = validateTitle($title);
if (!$titleValidation['valid']) {
    $errors['title'] = $titleValidation['message'];
}

$category = isset($_POST['category']) ? sanitizeInput($_POST['category']) : '';
$categoryValidation = validateCategory($category);
if (!$categoryValidation['valid']) {
    $errors['category'] = $categoryValidation['message'];
}

$ingredients = isset($_POST['ingredients']) ? sanitizeInput($_POST['ingredients']) : '';
$ingredientsValidation = validateIngredients($ingredients);
if (!$ingredientsValidation['valid']) {
    $errors['ingredients'] = $ingredientsValidation['message'];
}

$description = isset($_POST['description']) ? sanitizeInput($_POST['description']) : '';
$descriptionValidation = validateDescription($description);
if (!$descriptionValidation['valid']) {
    $errors['description'] = $descriptionValidation['message'];
}

$tags = $_POST['tags'] ?? [];
$tags = array_map('sanitizeInput', $tags);
$tagsValidation = validateTags($tags);
if (!$tagsValidation['valid']) {
    $errors['tags'] = $tagsValidation['message'];
}

$steps = [];
if (isset($_POST['steps'])) {
    if (is_array($_POST['steps'])) {
        $steps = array_map('sanitizeInput', $_POST['steps']);
        $steps = array_filter($steps, function($step) {
            return !empty(trim($step));
        });
    } else {
        $stepsText = sanitizeInput($_POST['steps']);
        $steps = explode("\n", $stepsText);
        $steps = array_filter($steps, function($step) {
            return !empty(trim($step));
        });
    }
}

$stepsValidation = validateSteps($steps);
if (!$stepsValidation['valid']) {
    $errors['steps'] = $stepsValidation['message'];
}

if (!empty($errors)) {
    setFormErrors($errors);
    header('Location: /public/recipe/create.php');
    exit;
}

$recipeData = [
    'id' => uniqid(),
    'title' => $title,
    'category' => $category,
    'ingredients' => is_string($ingredients) ? explode("\n", $ingredients) : $ingredients,
    'description' => $description,
    'tags' => $tags,
    'steps' => $steps,
    'created_at' => date('Y-m-d H:i:s')
];

$saved = saveRecipe($recipeData);

if ($saved) {
    header('Location: /public/index.php');
    exit;
} else {
    $errors['general'] = 'Failed to save recipe. Please try again.';
    setFormErrors($errors);
    header('Location: /public/recipe/create.php');
    exit;
}