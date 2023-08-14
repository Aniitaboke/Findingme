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

    // Define recipient result variable
$recipient_result = NULL;
    
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $recipient_account_number = $_POST['recipient_account_number'];
        $amount = $_POST['amount'];

        // Check if amount is within the limit
        if ($amount < 50 || $amount > 40000000) {
            // Display error message
            echo "Amount must be between 50 and 40,000,000";
        } else {
            // Retrieve recipient details from registerform table
            $recipient_sql = "SELECT * FROM registerform WHERE account_number = $recipient_account_number";
            $recipient_result = mysqli_query($conn, $recipient_sql);
        }

        
        
        // Check if recipient details were found
        if ($recipient_result !== null && mysqli_num_rows($recipient_result) > 0) {
       
            // Fetch recipient details
            $recipient_row = mysqli_fetch_assoc($recipient_result);
            $recipient_name = $recipient_row['name'];
            $recipient_balance = $recipient_row['balance'];
            
            // Check if sender has enough balance to send the amount
            if ($amount <= $row['balance']) {
                // Deduct amount from sender's account
                $new_sender_balance = $row['balance'] - $amount;
                $update_sender_sql = "UPDATE registerform SET balance = $new_sender_balance WHERE id = $user_id";
                mysqli_query($conn, $update_sender_sql);
                
                // Add amount to recipient's account
                
                $new_recipient_balance = $recipient_balance + $amount;
                $update_recipient_sql = "UPDATE registerform SET balance = $new_recipient_balance WHERE account_number = $recipient_account_number";
                mysqli_query($conn, $update_recipient_sql);
                
                // Display success message
                echo "You have successfully sent $amount to $recipient_name ( Account No. $recipient_account_number)";
            } else {
                // Display error message
                echo "Insufficient balance to send $amount to $recipient_name ( Account No. $recipient_account_number)";
            }
        } else {
            // Display error message
            echo "-Invalid amount";
        }
        
        
    }

} else {
    // Display error message
    echo "User details not found";
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title> Send Money</title>
    <style>
        .main{
            background: linear-gradient(to top, rgba(0,0,0,0.5)50%, rgba(0,0,0,0.5)50%), url(money-image\ 4.png);
    padding-top: 40px;
    color: black;
    font-family: sans-serif;
    font-weight: bold;
        }
        /* Apply styles to form 1 */
#form1 {
    background: linear-gradient(to bottom, #ffffff, #f5f5f5);
    max-width: 400px;
			margin: 0 auto;
			background-color: #fff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: absolute;
            margin-left: 300px;
}


/* Apply styles to form 2 */
#form2 {
    
    
    width: fit-content;
			margin: 0 auto;
			background-color: #fff;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-right: 300px;
            
}

#form2 input[type=text] {
    width: 100px;
    padding: 5px;
    margin-bottom: 10px;
}

#form2 button[type=submit] {
    text-decoration: none;
           padding: 5px 10px;
           margin-left: 250px;
            border-radius: 3px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
    
}

        
        label {
            display: flex; 
            width: 150px;
            text-align: right;
            margin-right: 10px;

            
        }
        input[type="text"], input[type="number"] {
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 16px;
            border: 1px solid black;
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
 
 <form id="form1" action="#" method="post">
 
    <!-- Form fields go here -->

   <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><label>Account Number:</label> <?php echo $account_number; ?></p>
    </form>


    <form id="form2" action="#" method="post">
    <!-- Form fields go here -->

    <label for="recipient_account_number">Recipient Account Number:</label>
    <input type="text" name="recipient_account_number" required><br>
    
    <label for="amount">Amount:</label>
    <input type="number" name="amount" min="1" required><br><br>

    <button type="submit">Send Money</button> <br> <br>
    <a href="index4.html">BACK</a>
  </form>
 </body>
</html> 