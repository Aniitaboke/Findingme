<?php
    // Start a session to store user data
    session_start();

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Collect form data and sanitize inputs
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Validate inputs
        if (empty($name)) {
            $error = "Please enter your name";
        } elseif (empty($email)) {
            $error = "Please enter your email address";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address";
        } elseif (empty($password)) {
            $error = "Please enter a password";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long";
        } else {
            // Connect to database
            $host = "localhost";
            $user = "root";
            $pass = "";
            $db = "user_db";
            $conn = mysqli_connect($host, $user, $pass, $db);

            // Check if email already exists in database
            $sql = "SELECT * FROM registerform WHERE email='$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $error = "Email address already registered";
            } else {
                // Insert user data into database
                $sql = "INSERT INTO registerform (name, email, password) VALUES ('$name', '$email', '$password')";
                if (mysqli_query($conn, $sql)) {
                    // User has been registered successfully
                    $_SESSION["email"] = $email;
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error registering user";
                }
            }
        }

        // Display error message if there is one
        if (!empty($error)) {
            echo "<p>$error</p>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="main">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register"><br><br><br><br>
        <p class="one"> Already have an account?<a href="login.php">Login Here </a><br><br><br>
        <a href="index4.html">BACK</a></p>

    </form>
</body>
</html>


