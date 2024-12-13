<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'includes/db.php';

session_start();

$error_message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;

            header('Location: index.php');
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
        $connection->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="images/cookbook_corner_logo.svg" type="image/svg+xml">
    <link href="general.css" rel="stylesheet">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-container">
            <h2>Register</h2>

            <?php if ($error_message): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" autocomplete="on">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" autocomplete="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" autocomplete="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" required>

                <button type="submit">Register</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a></p>
            <p><a href="index.php">Enter as Guest</a></p>
        </div>
    </div>

</body>
</html>
