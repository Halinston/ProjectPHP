<?php
session_start();

$valid_username = "test";
$valid_password = "test123";

// Check if the user is logging out
if (isset($_GET['logout'])) {
    // Clear the session
    session_unset();
    session_destroy();

    // Clear the "Remember me" cookie
    setcookie("username", "", time() - 3600, "/");

    // Redirect to the login page after logout
    header("Location: login.php");
    exit();
}

// Check if the user is already logged in via cookie
if (isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
}

// Check if the login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;

        // Set cookie if "Remember me" is checked
        if ($remember) {
            setcookie("username", $username, time() + (7 * 24 * 60 * 60), "/");
        }

        header("Location: login.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

$logged_in = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <?php if ($logged_in): ?>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="welcome">You are logged in.</p>
        <a href="login.php?logout=true" class="logout">Log out</a>
    <?php else: ?>
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
