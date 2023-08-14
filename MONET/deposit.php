<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user details from the registerform table
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM registerform WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $account_number = $row['account_number'];
} else {
    echo "User not found";
    exit;
}
  
// Retrieve user details from the registerform table
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM registerform WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $email = $row['email'];
    $account_number = $row['account_number'];
    
    // Check if the user has an account in the accountform table
    $sql = "SELECT * FROM registerform WHERE account_number = '$account_number'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 0) {
        // User does not have an account, so create a new account with a balance of 0
        $sql = "INSERT INTO registerform (account_number, balance) VALUES ('$account_number', 0)";
        if (mysqli_query($conn, $sql)) {
            echo "Account created successfully";
        } else {
            echo "Error creating account: " . mysqli_error($conn);
        }
    }
} else {
    echo "User not found";
    exit;
}

// Retrieve account balance from accountform table
$balance = 0;
$sql = "SELECT balance FROM registerform WHERE account_number = '$account_number'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $balance = $row['balance'];
} else {
    echo "Account not found";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit'])) {
    $deposit_amount = $_POST['deposit_amount'];
    
    // Check if deposit amount is within the allowed range
    if ($deposit_amount >= 50 && $deposit_amount <= 50000000) {
        $new_balance = $balance + $deposit_amount;
        

        // Update the accountform table with the new balance
        $sql = "UPDATE registerform SET balance = $new_balance WHERE account_number = '$account_number'";
        if (mysqli_query($conn, $sql)) {
            $balance = $new_balance;
            $_SESSION['deposit_success'] = true;
            header("Location: deposit.php");
            exit;
        } else {
            echo "Error updating account balance: " . mysqli_error($conn);
        }
    } else {
        echo "Deposit amount should be between 50 and 50,000,000";
    } 
}

// Check for deposit success message
if (isset($_SESSION['deposit_success']) && $_SESSION['deposit_success']) {
    echo "Deposit successful";
    unset($_SESSION['deposit_success']);
}



mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deposit</title>
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
            display: inline-flexbox; 
            width: 150px;
            text-align: right;
            margin-right: 10px;
            
        }
        input[type="text"], input[type="number"] {
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            margin-left: 200px;
            border-radius: 3px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        p a{
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
    <h1>Deposit Money</h1>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    
    
    <p><label>Account Number:</label> <?php echo $account_number; ?></p>
    
        <p><label>Balance:</label> $<?php echo number_format($balance, 2); ?></p><br>
        <p>
    <label for="deposit_amount">Deposit Amount:</label>
    <input type="number" name="deposit_amount" id="deposit_amount" required>
</p>
<p>
    <input type="submit" name="deposit" value="Deposit"> <br> <br> <br>
    <a href="index4.html">BACK</a>
</p>
</form>

</body>
</html>
