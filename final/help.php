<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once 'includes/db.php';

// Function to handle the "Surprise!" button click
if (isset($_GET['surprise'])) {
    $randomRecipeStmt = $connection->prepare('SELECT id FROM recipes_test_run ORDER BY RAND() LIMIT 1');
    $randomRecipeStmt->execute();
    $randomRecipe = $randomRecipeStmt->get_result()->fetch_assoc();
    
    if ($randomRecipe) {
        header('Location: recipe.php?id=' . $randomRecipe['id']);
        exit();
    } else {
        echo "<script>alert('Sorry, we couldn’t find a recipe at the moment. Please try again!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookBook Corner Help Page</title>
    <link rel="icon" href="images/cookbook_corner_logo.svg" type="image/svg+xml">
    <link href="general.css" rel="stylesheet">
</head>
<body>
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

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- If logged in, show Logout link -->
                <a href="logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <!-- If not logged in, show Login link -->
                <a href="login.php" class="nav-link">Login</a>
            <?php endif; ?>

        </div>
        <form action="all-recipes.php" method="get" class="search-bar">
            <input type="text" name="search" id="searchInput" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <hr class="separator">

    <div class="help-container">
        <h2>Welcome to the Help Page!</h2>
        <p>Here at CookBook Corner, we aim to make your cooking experience enjoyable and straightforward. Below are some helpful tips on how to navigate our website:</p>
        
        <h3>Finding Recipes</h3>
        <p>To explore our collection of recipes, click on the "All Recipes" link in the navigation menu. You can filter recipes by cuisine, protein, diet, and sorting options to find the perfect dish for your needs.</p>

        <h3>Viewing Recipe Details</h3>
        <p>Once you select a recipe, you will see the recipe header that includes the title, description, cooking time, servings, and calorie count. You'll also find a list of ingredients and step-by-step instructions to help you through the cooking process.</p>

        <h3>Using the Search Functionality</h3>
        <p>If you're looking for a specific recipe, use the search bar at the top of the page. Just type in the name of the dish or an ingredient, and hit the "Search" button.</p>

        <h3>Enjoy Cooking!</h3>
        <p>We hope you find the perfect recipes and enjoy cooking delicious meals with CookBook Corner!</p>
    
        <div class="button-container">
                <a href="case-study.html" class="solution-button" target="_blank">View Case Study</a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
