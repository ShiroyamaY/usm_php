<?php
require_once __DIR__ . '/../src/helpers.php';

$allRecipes = readRecipes();
$latestRecipes = array_slice($allRecipes, -2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Book - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .navigation {
            margin-bottom: 20px;
        }
        .navigation a {
            text-decoration: none;
            color: #2196F3;
            margin-right: 15px;
        }
        .recipe-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .recipe-title {
            margin-top: 0;
            color: #4CAF50;
        }
        .recipe-meta {
            margin: 10px 0;
            color: #666;
            font-size: 14px;
        }
        .tag {
            display: inline-block;
            background-color: #e1f5fe;
            color: #0288d1;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 12px;
        }
        .buttons {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            margin: 0 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .empty-message {
            text-align: center;
            color: #666;
            margin: 50px 0;
        }
    </style>
</head>
<body>
<div class="navigation">
    <a href="/public">Home</a>
    <a href="/public/recipe">All Recipes</a>
</div>

<h1>Recipe Book</h1>

<h2>Latest Recipes</h2>

<?php if (empty($latestRecipes)): ?>
    <div class="empty-message">
        <p>No recipes yet. Add your first recipe!</p>
    </div>
<?php else: ?>
    <?php foreach ($latestRecipes as $recipe): ?>
        <div class="recipe-card">
            <h3 class="recipe-title"><?php echo $recipe->title; ?></h3>
            <div class="recipe-meta">
                <strong>Category:</strong> <?php echo ucfirst(str_replace('_', ' ', $recipe->category)); ?> |
                <strong>Added:</strong> <?php echo formatDate($recipe->created_at); ?>
            </div>
            <p><?php echo $recipe->description; ?></p>
            <div>
                <?php foreach ($recipe->tags as $tag): ?>
                    <span class="tag"><?php echo ucfirst(str_replace('_', ' ', $tag)); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="buttons">
    <a href="/public/recipe/create.php" class="btn">Add New Recipe</a>
    <a href="/public/recipe" class="btn">View All Recipes</a>
</div>
</body>
</html>