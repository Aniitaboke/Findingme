<?php

// Start session
session_start();

// Define the database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user ID from session variable
$user_id = $_SESSION['user_id'];

// Retrieve user details from registerform table
$sql = "SELECT * FROM registerform WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

// Check if any user details were returned
if (mysqli_num_rows($result) > 0) {
    // Fetch user details
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $account_number = $row['account_number'];

    // Retrieve account balance from accountform table
    $sql = "SELECT balance FROM registerform WHERE account_number = '$account_number'";
    $result = mysqli_query($conn, $sql);

    // Check if any account balance was returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch account balance
        $row = mysqli_fetch_assoc($result);
        $balance = $row['balance'];
    } else {
        // Set balance to 0 if no account balance was found
        $balance = 0;
    }

    

    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        // Retrieve the new balance from the form
        $new_balance = $_POST['new_balance'];

        // Update the account balance in the accountform table
        $sql = "UPDATE registerform SET balance = $new_balance WHERE account_number = '$account_number'";
        if (mysqli_query($conn, $sql)) {
            echo "Account balance updated successfully";
        } else {
            echo "Error updating account balance: " . mysqli_error($conn);
        }
    }
} else {
    echo "No account balance found for this user";
}

// Close database connection
mysqli_close($conn);

?> 




<!DOCTYPE html>
<html>
<head>
    <title>Check Balance</title>
    <style>
        .main{
            background: linear-gradient(to top, rgba(0,0,0,0.5)50%, rgba(0,0,0,0.5)50%), url(money-image\ 4.png);
            padding-top: 40px;
            color: black;
            font-family: sans-serif;
            font-weight: bold;
    
        }
        form{
            background: linear-gradient(to bottom, #ffffff, #f5f5f5);
            max-width: 400px;
			margin: 0 auto;
			background-color: #fff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="submit"] {
            display: block;
            margin-bottom: 20px;
            padding: 5px;
            width: 300px;
        }
         a{
            text-decoration: none;
           padding: 5px 10px;
            border-radius: 3px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer; 
        }
        

    </style>
</head>
<body class="main">
   
    <form method="post">
    <h1>Balance</h1>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><label>Account Number:</label> <?php echo $account_number; ?></p>
    <p><label>Balance:</label> $<?php echo number_format($balance, 2); ?></p><br>
    
    <a href="index4.html">BACK</a>
</p>
</form>
    
</body>
</html>
