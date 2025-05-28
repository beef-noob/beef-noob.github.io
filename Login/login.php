<?php
session_start();

// Path to the text file for storing user info
$users_file = "users.txt";

// Handle registration (if coming from register form)
if (isset($_POST["register"])) {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username && $password) {
        // Save username and password (plain text, NOT SECURE, for demo only)
        file_put_contents($users_file, "$username|$password\n", FILE_APPEND);
        $register_message = "Registration successful! You can now log in.";
    } else {
        $register_message = "Please enter both username and password.";
    }
}

// Handle login
if (isset($_POST["login"])) {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $found = false;

    if (file_exists($users_file)) {
        $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($saved_user, $saved_pass) = explode("|", $line);
            if ($username === $saved_user && $password === $saved_pass) {
                $found = true;
                break;
            }
        }
    }

    if ($found) {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $username;
        header("Location: ../Base.html");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - S.I.M.P</title>
    <link rel="stylesheet" href="../Style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">S.I.M.P</div>
        <ul class="nav-links">
            <li><a href="../Base.html">Home</a></li>
            <li><a href="../Base.html#features">Features</a></li>
            <li><a href="../Base.html#about">About</a></li>
            <li><a href="regester.html">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
    <section class="register-section">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($register_message)): ?>
            <p style="color: green; text-align: center;"><?php echo htmlspecialchars($register_message); ?></p>
        <?php endif; ?>
        <form class="register-form" method="post" action="login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <h2 style="margin-top:2rem;">Register</h2>
        <form class="register-form" method="post" action="login.php">
            <label for="reg_username">Username</label>
            <input type="text" id="reg_username" name="username" required>

            <label for="reg_password">Password</label>
            <input type="password" id="reg_password" name="password" required>

            <button type="submit" name="register">Register</button>
        </form>
    </section>
    <footer>
        &copy; 2024 S.I.M.P. All rights reserved.
    </footer>
</body>
</html>