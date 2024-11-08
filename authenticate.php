<?php

session_start();
include 'validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST['user']);
    $password = test_input($_POST['pwd']);

    $conn = new mysqli("localhost", "root", "", "softball");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("location: games.php");
            exit;
        } else {
            echo "Invalid credentials";
        }
    } else {
        echo "Invalid credentials";
    }

    $conn->close();
}
?>
