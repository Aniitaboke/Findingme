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
} else {
    echo "User not found";
    exit;
}

// Handle withdrawal form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])  && isset($_POST['withdraw_amount']) && $_POST['withdraw_amount'] != "") {
    $withdraw_amount = $_POST['withdraw_amount'];

    if ($withdraw_amount >= 50 && $withdraw_amount <= 50000000) {
        $error_message = "Insufficient funds";
        $new_balance = $balance - $withdraw_amount;

        // Update the accountform table with the new balance
        $sql = "UPDATE registerform SET balance = $new_balance WHERE account_number = '$account_number'";
        if (mysqli_query($conn, $sql)) {
            $balance = $new_balance;
            $_SESSION['withdraw_success'] = true;
            header("Location: withdraw.php");
            exit;
            
        } else {
            echo "Error updating account balance: " . mysqli_error($conn);
        }
    } else {
        echo "withdraw amount should be between 50 and 50,000,000";
    }
}

// Check for withdraw success message
if (isset($_SESSION['withdraw_success']) && $_SESSION['withdraw_success']) {
    echo "Withdraw successful";
    unset($_SESSION['withdraw_success']);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw</title>
    <style>
        .main{
            background: linear-gradient(to top, rgba(0,0,0,0.5)50%, rgba(0,0,0,0.5)50%), url(money-image\ 4.png);
    padding-top: 40px;
    color: black;
    font-family: sans-serif;
    font-weight: bold;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        form{
            background-color: linear-gradient(to bottom, #ffffff, #f5f5f5);
            max-width: 400px;
			margin: 0 auto;
			background-color: #fff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="number"] {
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            padding: 5px 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .message {
            margin-top: 10px;
            padding: 5px;
            border-radius: 3px;
        }
        .error {
            background-color: darkolivegreen;
            border: 1px solid #ffb3b3;
            color: whitesmoke;
        }
        .success {
            background-color: darkolivegreen;
            border: 1px solid #b3ffb3;
            color: #008000;
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
<form method="POST">
    <h1>Withdraw</h1>
    <p>Welcome, <?php echo $name; ?>!</p>
    <p>Your current balance is: $<?php echo $balance; ?></p>

<?php if (isset($error_message)): ?>
    <div class="message error"><?php echo $error_message; ?></div>
<?php endif; ?>



    <label for="withdraw_amount">Withdraw Amount:</label>
    <input type="number" id="withdraw_amount" name="withdraw_amount" required><br><br>
    <input type="submit" name="withdraw" value="Withdraw"><br><br><br>
    <a href="index4.html">BACK</a>
</form>
</body>
</html>

