<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once 'includes/db.php';

$search = $_GET['search'] ?? '';
$cuisine = $_GET['cuisine'] ?? '';
$protein = $_GET['protein'] ?? '';
$diet = $_GET['diet'] ?? ''; 
$sort_by = $_GET['sort_by'] ?? '';


$sql = 'SELECT * FROM recipes_test_run WHERE 1=1';
$params = [];
$types = '';

// search filter
if (!empty($search)) {
    $sql .= ' AND (title LIKE ? OR ingredients LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
    $types .= 'ss';
}

// cuisine filter
if (!empty($cuisine)) {
    $sql .= ' AND cuisine = ?';
    $params[] = $cuisine;
    $types .= 's';
}

//  protein filter
if (!empty($protein)) {
    $sql .= ' AND protein = ?';
    $params[] = $protein;
    $types .= 's';
}

//  diet filter
if (!empty($diet)) {
    $sql .= ' AND protein = ?';
    $params[] = $diet; 
    $types .= 's';
}

//  sorting
if (!empty($sort_by)) {
    if ($sort_by === 'cook_time') {
        $sql .= ' ORDER BY cook_time ASC'; 
    } elseif ($sort_by === 'alphabetical') {
        $sql .= ' ORDER BY title ASC';
    }
}

$statement = $connection->prepare($sql);

if (!empty($params)) {
    $statement->bind_param($types, ...$params);
}

$statement->execute();
$recipes = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

$result = $connection->query("SELECT COUNT(*) AS total FROM recipes_test_run");
$totalRecipes = $result->fetch_assoc()['total'];

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
    <title>CookBook Corner All Recipes Page</title>
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
            <input type="text" name="search" id="searchInput" placeholder="Search for recipes..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button> 
        </form>

    </div>
    <hr class="separator">

    <!-- Filter Area -->
    <div class="filter-area">
        <h2 class="filter-title">
            <?php
            if (empty($recipes)) {
                echo 'No recipes found matching your criteria.';
            } else {
                if (!empty($search)) {
                    echo 'Results for: "' . htmlspecialchars($search) . '"';
                } elseif (!empty($cuisine)) {
                    echo 'Cuisine: ' . htmlspecialchars(ucfirst($cuisine));
                } elseif (!empty($protein)) {
                    echo 'Protein: ' . htmlspecialchars(ucfirst($protein));
                } elseif (!empty($diet)) {
                    echo 'Diet: ' . htmlspecialchars(ucfirst($diet));
                } else {
                    echo 'All Recipes';
                }
            }
            ?>
        </h2>
        <form action="all-recipes.php" method="get">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <div class="dropdowns-container">
                <select name="cuisine" class="dropdown">
                    <option value="">CUISINE</option>
                    <option value="american" <?php echo $cuisine === 'american' ? 'selected' : ''; ?>>American</option>
                    <option value="chinese" <?php echo $cuisine === 'chinese' ? 'selected' : ''; ?>>Chinese</option>
                    <option value="italian" <?php echo $cuisine === 'italian' ? 'selected' : ''; ?>>Italian</option>
                    <option value="mexican" <?php echo $cuisine === 'mexican' ? 'selected' : ''; ?>>Mexican</option>
                    <option value="middle eastern" <?php echo $cuisine === 'middle eastern' ? 'selected' : ''; ?>>Middle Eastern</option>
                    <option value="mediterranean" <?php echo $cuisine === 'mediterranean' ? 'selected' : ''; ?>>Mediterranean</option>
                    <option value="seasonal" <?php echo $cuisine === 'seasonal' ? 'selected' : ''; ?>>Seasonal</option>
                    <option value="korean" <?php echo $cuisine === 'korean' ? 'selected' : ''; ?>>Korean</option>
                    <option value="Japanese" <?php echo $cuisine === 'japanese' ? 'selected' : ''; ?>>Japanese</option>
                </select>
                <select name="protein" class="dropdown">
                    <option value="">PROTEIN</option>
                    <option value="beef" <?php echo $protein === 'beef' ? 'selected' : ''; ?>>Beef</option>
                    <option value="chicken" <?php echo $protein === 'chicken' ? 'selected' : ''; ?>>Chicken</option>
                    <option value="pork" <?php echo $protein === 'pork' ? 'selected' : ''; ?>>Pork</option>
                    <option value="fish" <?php echo $protein === 'fish' ? 'selected' : ''; ?>>Fish</option>
                    <option value="turkey" <?php echo $protein === 'turkey' ? 'selected' : ''; ?>>Turkey</option>
                </select>
                <select name="diet" class="dropdown">
                    <option value="">DIET</option>
                    <option value="vegetarian" <?php echo $diet === 'vegetarian' ? 'selected' : ''; ?>>Vegetarian</option>
                    <option value="gluten-free" <?php echo $diet === 'gluten-free' ? 'selected' : ''; ?>>Gluten Free</option>
                    <option value="vegan" <?php echo $diet === 'vegan' ? 'selected' : ''; ?>>Vegan</option>
                </select>
                <select name="sort_by" class="dropdown">
                    <option value="">SORT BY</option>
                    <option value="cook_time" <?php echo $sort_by === 'cook_time' ? 'selected' : ''; ?>>Time</option>
                    <option value="alphabetical" <?php echo $sort_by === 'alphabetical' ? 'selected' : ''; ?>>Alphabetical</option>
                </select>
            </div>
            <div class="filter-search-button-container">
                <button type="submit" class="filter-search-button">Filter</button>
            </div>
            <div class="clear-all-container">
                <a href="all-recipes.php" class="clear-all-text">Clear All</a>
            </div>
        </form>
    </div>
    
    <hr class="separator">

    <div class="container">
    <?php foreach ($recipes as $recipe): ?>
        <a href="recipe.php?id=<?php echo $recipe['id']; ?>">
            <div class="card">
                <img src="images/<?php echo ($recipe['main_image']); ?>" alt="Recipe Image" class="pic">
                <h3 class="recipe-title"><?php echo ($recipe['title']); ?></h3>
                <h4 class="recipe-subtitle"><?php echo ($recipe['subtitle']); ?></h4>
                <div class="time-indicator"><?php echo ($recipe['cook_time']); ?></div>
            </div>
        </a>
        
    <?php endforeach; ?>
    <?php if (count($recipes) > 0): ?>

    <?php else: ?>
        <p class="error">No recipes found matching "<?php echo ($search); ?>"</p>
    <?php endif; ?>
</div>
    
    

<script src="script.js"></script>
</body>
</html>