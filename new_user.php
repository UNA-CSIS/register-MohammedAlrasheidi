<?php
session_start();
include 'validate.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST['user']);
    $password = test_input($_POST['pwd']);
    $password_confirm = test_input($_POST['repeat']);

    if ($password !== $password_confirm) {
        echo "Passwords do not match!";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "softball");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $username, $hashed_password);
        if ($insert_stmt->execute()) {
            $_SESSION['username'] = $username;
            header("location: games.php");
            exit;
        } else {
            echo "Error: " . $insert_stmt->error;
        }
    }

    $conn->close();
}
?>
