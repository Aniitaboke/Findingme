<?php
// Start a new session
session_start();


// Check if username and password are correct
// ...


// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the login form has been submitted
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Fetch user from the database
  $sql = "SELECT * FROM registerform WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // Login successful, store user data in session
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];

    // Redirect to the dashboard page
    header("Location: index.html");
    exit;
  } else {
    // Login failed, show error message
   $login_error = "Invalid email or password.";

  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="main">

  <h1>Login</h1>


  <form method="post">
    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <input type="submit" name="submit" value="Login">
    <?php if (isset($login_error)) { ?>
        <div><?php echo $login_error; ?></div>
    <?php } ?>
    
    <p class="one"> Don't have an account?<a href="register.php">Register Here </a></p>
  </form>

</body>
</
