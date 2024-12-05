<?php


require_once 'includes/db.php';

// Randomly select a single recipe for "Pick of the Day"
$pickOfDayStmt = $connection->prepare('SELECT * FROM recipes_test_run ORDER BY RAND() LIMIT 1');
$pickOfDayStmt->execute();
$pickOfDay = $pickOfDayStmt->get_result()->fetch_assoc();

// Randomly select four recipes for "Latest Recipes"
$latestRecipesStmt = $connection->prepare('SELECT * FROM recipes_test_run ORDER BY RAND() LIMIT 4');
$latestRecipesStmt->execute();
$latestRecipes = $latestRecipesStmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
    <title>CookBook Corner Index Page</title>
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
        </div>
        <form action="all-recipes.php" method="get" class="search-bar">
            <input type="text" name="search" id="searchInput" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <hr class="separator">

    <!-- Pick of the Day -->
    <div class="rectangle-section">
        <div class="left-side">
            <div class="pick-of-the-day-text"><u>Pick of the Day</u></div>
            <h2 class="rec-title"><?php echo ($pickOfDay['title']); ?></h2>
            <h3 class="rec-subtitle"><?php echo ($pickOfDay['subtitle']); ?></h3>
            <p class="description"><?php echo ($pickOfDay['description']); ?></p>
            <a href="recipe.php?id=<?php echo $pickOfDay['id']; ?>" class="view-recipe-button">VIEW RECIPE</a>
        </div>
        <div class="right-side">
            <img src="images/<?php echo ($pickOfDay['main_image']); ?>" alt="Pick of the Day Image" class="image">
        </div>
    </div>

    <!-- Latest Recipes -->
    <div class="latest-recipes-section">
        <h2 class="latest-recipes-title"><u>Latest Recipes</u></h2>
        <div class="latest-recipes-container">
            <?php foreach ($latestRecipes as $recipe): ?>
                <div class="recipe-box">
                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-link">
                        <img src="images/<?php echo ($recipe['main_image']); ?>" alt="Recipe Image" class="latest-recipe-image">
                        <h3 class="recipe-title"><?php echo ($recipe['title']); ?></h3>
                        <h4 class="recipe-subtitle"><?php echo ($recipe['subtitle']); ?></h4>
                        <div class="time-indicator"><?php echo ($recipe['cook_time']); ?> MIN</div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
