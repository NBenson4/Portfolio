<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_account'])) {
    // Database configuration
    $servername = "localhost";
    $username = "ns108";
    $password = "=X2yD>P48=f(";
    $dbname = "ns108";

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get the input data
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    // Check for empty inputs (Server-side validation)
    if (empty($inputUsername) || empty($inputPassword) || empty($firstName) || empty($lastName)) {
        echo "Error: All fields are required.";
        exit();
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: Username already taken.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $inputUsername, $hashedPassword, $firstName, $lastName);


        if ($stmt->execute()) {
            // Redirect on success
            header("Location: success.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the statement and the database connection
    $stmt->close();
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    <h2>Create New Account</h2>
    <form action="process_login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required minlength="8"><br><br>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required><br><br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required><br><br>
        <input type="submit" value="Create New Login" name="create_account">
    </form>
</body>
</html>