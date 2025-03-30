<?php
require_once __DIR__ . '/../../src/helpers.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;

$allRecipes = readRecipes();
$allRecipes = array_reverse($allRecipes);

$paginationData = getPaginatedRecipes($allRecipes, $page, $perPage);
$recipes = $paginationData['recipes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
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
        .ingredients, .steps {
            margin-bottom: 15px;
        }
        .ingredients h4, .steps h4 {
            margin-bottom: 5px;
            color: #333;
        }
        .tag {
            display: inline-block;
            background-color: #e1f5fe;
            color: #0288d1;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 5px;
            margin-bottom: 5px;
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
        }
        .btn:hover {
            background-color: #45a049;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #2196F3;
        }
        .pagination .current {
            background-color: #2196F3;
            color: white;
            border-color: #2196F3;
        }
        .pagination .disabled {
            color: #999;
            cursor: not-allowed;
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
    <a href="/public/recipe/create.php">Add Recipe</a>
</div>

<h1>All Recipes</h1>

<?php if (empty($recipes)): ?>
    <div class="empty-message">
        <p>No recipes found. Be the first to add a recipe!</p>
        <div class="buttons">
            <a href="/public/recipe/create.php" class="btn">Add New Recipe</a>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($recipes as $recipe): ?>
        <div class="recipe-card">
            <h3 class="recipe-title"><?php echo $recipe->title; ?></h3>
            <div class="recipe-meta">
                <strong>Category:</strong> <?php echo ucfirst(str_replace('_', ' ', $recipe->category)); ?> |
                <strong>Added:</strong> <?php echo formatDate($recipe->created_at); ?>
            </div>

            <p><?php echo $recipe->description; ?></p>

            <div class="ingredients">
                <h4>Ingredients:</h4>
                <ul>
                    <?php foreach ($recipe->ingredients as $ingredient): ?>
                        <li><?php echo $ingredient; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="steps">
                <h4>Preparation Steps:</h4>
                <ol>
                    <?php foreach ($recipe->steps as $step): ?>
                        <li><?php echo $step; ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div>
                <?php foreach ($recipe->tags as $tag): ?>
                    <span class="tag"><?php echo ucfirst(str_replace('_', ' ', $tag)); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <?php if ($paginationData['totalPages'] > 1): ?>
        <div class="pagination">
            <?php if ($paginationData['hasPrevPage']): ?>
                <a href="?page=<?php echo $paginationData['currentPage'] - 1; ?>">Previous</a>
            <?php else: ?>
                <span class="disabled">Previous</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $paginationData['totalPages']; $i++): ?>
                <?php if ($i == $paginationData['currentPage']): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paginationData['hasNextPage']): ?>
                <a href="?page=<?php echo $paginationData['currentPage'] + 1; ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="buttons">
        <a href="/public/recipe/create.php" class="btn">Add New Recipe</a>
    </div>
<?php endif; ?>
</body>
</html>