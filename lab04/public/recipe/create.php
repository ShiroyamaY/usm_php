<?php
require_once __DIR__ . '/../../src/helpers.php';

// Get validation errors if they exist
$errors = getFormErrors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .step-container {
            margin-bottom: 10px;
        }
        .add-step-btn {
            background-color: #2196F3;
            margin-bottom: 15px;
        }
        .navigation {
            margin-bottom: 20px;
        }
        .navigation a {
            text-decoration: none;
            color: #2196F3;
            margin-right: 15px;
        }
    </style>
</head>
<body>
<div class="navigation">
    <a href="/public/">Home</a>
    <a href="/public/recipe/">All Recipes</a>
</div>

<h1>Add New Recipe</h1>

<form action="/src/handlers/recipe_handler.php" method="POST">
    <div class="form-group">
        <label for="title">Recipe Title</label>
        <input type="text" id="title" name="title" required>
        <?php if (isset($errors['title'])): ?>
            <div class="error"><?php echo $errors['title']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <option value="appetizer">Appetizer</option>
            <option value="main_course">Main Course</option>
            <option value="dessert">Dessert</option>
            <option value="beverage">Beverage</option>
            <option value="soup">Soup</option>
            <option value="salad">Salad</option>
            <option value="breakfast">Breakfast</option>
            <option value="side_dish">Side Dish</option>
        </select>
        <?php if (isset($errors['category'])): ?>
            <div class="error"><?php echo $errors['category']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="ingredients">Ingredients (one per line)</label>
        <textarea id="ingredients" name="ingredients" rows="6" required></textarea>
        <?php if (isset($errors['ingredients'])): ?>
            <div class="error"><?php echo $errors['ingredients']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        <?php if (isset($errors['description'])): ?>
            <div class="error"><?php echo $errors['description']; ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="tags">Tags</label>
        <select id="tags" name="tags[]" multiple required>
            <option value="vegetarian">Vegetarian</option>
            <option value="vegan">Vegan</option>
            <option value="gluten_free">Gluten Free</option>
            <option value="dairy_free">Dairy Free</option>
            <option value="low_carb">Low Carb</option>
            <option value="high_protein">High Protein</option>
            <option value="quick">Quick & Easy</option>
            <option value="spicy">Spicy</option>
            <option value="seasonal">Seasonal</option>
        </select>
        <?php if (isset($errors['tags'])): ?>
            <div class="error"><?php echo $errors['tags']; ?></div>
        <?php endif; ?>
    </div>

    <!-- Advanced version with dynamic step addition -->
    <div class="form-group">
        <label>Preparation Steps</label>
        <div id="steps-container">
            <div class="step-container">
                <input type="text" name="steps[]" placeholder="Step 1" required>
            </div>
        </div>
        <button type="button" class="add-step-btn" id="add-step">Add Step</button>
        <?php if (isset($errors['steps'])): ?>
            <div class="error"><?php echo $errors['steps']; ?></div>
        <?php endif; ?>
    </div>

    <button type="submit">Add Recipe</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addStepBtn = document.getElementById('add-step');
        const stepsContainer = document.getElementById('steps-container');
        let stepCount = 1;

        addStepBtn.addEventListener('click', function() {
            stepCount++;
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step-container';
            stepDiv.innerHTML = `<input type="text" name="steps[]" placeholder="Step ${stepCount}" required>`;
            stepsContainer.appendChild(stepDiv);
        });
    });
</script>
</body>
</html>