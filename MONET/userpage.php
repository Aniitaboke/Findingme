<?php

// Start session
session_start();

// Connect to the user_db database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the last account number from the database
$sql = "SELECT account_number FROM registerform ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

// Check if any account number was returned
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $last_account_number = $row['account_number'];

   // Generate new unique account number
$account_number = rand(100, 999999);
$sql = "SELECT id FROM registerform WHERE account_number = '$account_number'";
$result = mysqli_query($conn, $sql);
while (mysqli_num_rows($result) > 0) {
    $account_number = rand(100, 999999);
    $sql = "SELECT id FROM registerform WHERE account_number = '$account_number'";
    $result = mysqli_query($conn, $sql);
}

	 

} else {
    // No account number found in the database, generate a new one
    $account_number = uniqid();
}



// Retrieve user details from registerform table
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM registerform WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

// Check if user exists
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    // Check if user already has an account number
    if (!empty($row['account_number'])) {
        $account_number = $row['account_number'];
    } else {
        // Insert account number into registerform table
        $user_id = $row['id'];
        $sql = "UPDATE registerform SET account_number = '$account_number' WHERE id = $user_id";
        if (!mysqli_query($conn, $sql)) {
            echo "Error assigning account number: " . mysqli_error($conn);
        }
    }

    // Display user details in form
    echo "<h1>User Details Form</h1>";
    echo "<form>";
    echo "<label>Name:</label>";
    echo "<input type='text' name='name' value='{$row['name']}' readonly>";

    echo "<label>Email:</label>";
    echo "<input type='email' name='email' value='{$row['email']}' readonly>";

    echo "<label>Account Number:</label>";
    echo "<input type='text' name='account_number' value='$account_number' readonly>";

} else {
    echo "User not found.";
}

// Close database connection
mysqli_close($conn);

?>


<!DOCTYPE html>
<html>
<head>
	<title>User Details Form</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 16px;
			line-height: 1.5;
			background-color: #f8f8f8;
			padding: 20px;
		}
        .main{
            background: linear-gradient(to top, rgba(0,0,0,0.5)50%, rgba(0,0,0,0.5)50%), url(money-image\ 4.png);
    padding-top: 40px;
    color: black;
    font-family: sans-serif;
    font-weight: bold;
        }

		h1 {
			margin-bottom: 30px;
			text-align: center;
		}

		form {
			background-color: linear-gradient(to bottom, #ffffff, #f5f5f5);
			max-width: 600px;
			margin: 0 auto;
			background-color: #fff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
		}

		label {
			display: block;
			margin-bottom: 10px;
			font-weight: bold;
		}

		input[type="text"],
		input[type="email"],
		input[type="submit"] {
			display: block;
			margin-bottom: 20px;
			padding: 10px;
			width: 100%;
			border-radius: 5px;
			border: 1px solid #ccc;
			box-sizing: border-box;
			font-size: 16px;
			line-height: 1.5;
		}


		.error {
			color: red;
			margin-bottom: 10px;
			font-size: 14px;
		}

		.success {
			color: green;
			margin-bottom: 10px;
			font-size: 14px;
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
        a:hover{
            color: black;
        }
	</style>
</head>
<body class="main">
	
	<form>
		<?php while ($row = mysqli_fetch_assoc($result)) { ?>
			<label>Name:</label>
			<input type="text" name="name" value="<?php echo $row['name']; ?>" readonly>

			<label>Email:</label>
			<input type="email" name="email" value="<?php echo $row['email']; ?>" readonly>

			<?php if (isset($account_number)) { ?>
				<label>Account Number:</label>
				<input type="text" name="account_number" value="<?php echo $account_number; ?>" readonly>
				<?php } ?>
			<?php } ?>
            <a href="index.html">BACK</a>
		</form>
		<?php if (isset($message)) { ?>
			<div class="message">
				<?php echo $message; ?>
			</div>
		<?php } ?>
        
	</div>
</body>
</html>






