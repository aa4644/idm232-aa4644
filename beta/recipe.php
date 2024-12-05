<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Include database connection
require_once 'includes/db.php';

// Get the recipe ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<h1>Invalid Recipe ID</h1>";
    exit;
}

// Prepare the SQL statement to fetch the recipe details
$statement = $connection->prepare("SELECT * FROM recipes_test_run WHERE id = ?");
$statement->bind_param("i", $id);
$statement->execute();
$recipe = $statement->get_result()->fetch_assoc();

if (!$recipe) {
    echo "<h1>Recipe not found</h1>";
    exit;
}

// Exploding the ingredients column by '*' to get each ingredient
$ingredients = explode('*', $recipe['ingredients']);

// Exploding the steps column by '*' to get each step
$steps = explode('*', $recipe['steps']);

// Exploding the images column by '*' to get each image path
$stepImages = explode('*', $recipe['steps_image']);

// Function to handle the "Surprise!" button click
if (isset($_GET['surprise'])) {
    $randomRecipeStmt = $connection->prepare('SELECT id FROM recipes_test_run ORDER BY RAND() LIMIT 1');
    $randomRecipeStmt->execute();
    $randomRecipe = $randomRecipeStmt->get_result()->fetch_assoc();
    
    if ($randomRecipe) {
        // Redirect to the recipe page with the random recipe ID
        header('Location: recipe.php?id=' . $randomRecipe['id']);
        exit();
    } else {
        // If no random recipe found, display an error message
        echo "<script>alert('Sorry, we couldn’t find a recipe at the moment. Please try again!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($recipe['title']); ?> - Recipe Details</title>
    <link href="general.css" rel="stylesheet">
</head>
<body>
    <!-- Your existing header and navigation code -->
    <a href="index.php" class="logo-container">
        <img src="images/cookbook_corner_logo.svg" alt="CookBook Corner Logo" class="logo">
        <h1 class="logo-text">COOKBOOK CORNER</h1>
    </a>
    <hr class="separator">
    <div class="navigation-container">
        <div class="hamburger" id="hamburger">&#9776;</div>
        <div class="nav-buttons" id="nav-buttons">
            <div class="close-icon" id="close-icon">&times;</div>
            <a href="index.php" class="nav-link">Home</a>
            <a href="all-recipes.php" class="nav-link">All Recipes</a>
            <a href="index.php?surprise=true" class="nav-link">Surprise!</a>
            <a href="help.php" class="nav-link">Help</a>
        </div>
        <form action="all-recipes.php" method="get" class="search-bar">
            <input type="text" name="search" id="searchInput" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <hr class="separator">
    
    <div class="recipe-container">
        <!-- Recipe Header -->
        <div class="overview">
            <div class="description">
                <h1 class="title"><?php echo ($recipe['title']); ?></h1>
                <h3 class="subtitle"><?php echo ($recipe['subtitle']); ?></h3>
                <div class="badges">
                    <div class="badge"><?php echo ($recipe['cuisine']); ?></div>
                    <div class="badge"><?php echo ($recipe['protein']); ?></div>
                </div>
                <div class="recipe-info">
                    <div>COOK TIME: <?php echo ($recipe['cook_time']); ?></div>
                    <div>YIELD: <?php echo ($recipe['serving_size']); ?></div>
                    <div>CALORIES: <?php echo ($recipe['calories']); ?></div>
                </div>
                <p class="description"><?php echo nl2br(($recipe['description'])); ?></p>
            </div>
            <div class="recipe-image">
                <img src="images/<?php echo ($recipe['main_image']); ?>" alt="Recipe Image">
            </div>
        </div>
        <hr class="separator">

        
        <div class="ingredients">
            <div class="ingredients-left">
                <h2>Ingredients</h2>
                <ul>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <?php if (trim($ingredient)): ?>
                            <li><?php echo (trim($ingredient)); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="ingredients-image">
                <img src="images/<?php echo ($recipe['ingredients_image']); ?>" alt="Ingredients Image">
            </div>
        </div>
        <hr class="separator">


        <!-- Steps Section -->
        <div class="steps-detail">
        <?php foreach ($steps as $index => $step): ?>
                <?php if (trim($step)): ?>
                    <?php
                    // Splitting the step into a title and description using '^^'
                    $stepParts = explode('^^', $step);
                    ?>
                    <div class="step">
                        <!-- Step Instruction on the left -->
                        <div class="instruction">
                            <h3><?php echo ($index + 1) . '. ' . (trim($stepParts[0])); ?>:</h3>
                            <p><?php echo nl2br((trim($stepParts[1]))); ?></p>
                        </div>
                        <!-- Step Image on the right -->
                        <div class="steps-image">
                            <?php if (isset($stepImages[$index])): ?>
                                <img src="images/<?php echo (trim($stepImages[$index])); ?>" alt="Step Image <?php echo $index + 1; ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
