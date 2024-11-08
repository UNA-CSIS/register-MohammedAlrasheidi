<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "softball");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM games";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Games</title>
        </head>
        <body>
            <h1>Games List</h1>

            <table border="1">
                <tr>
                    <th>Game ID</th>
                    <th>Opponent</th>
                    <th>Site</th>
                    <th>Result</th>
                </tr>

                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['opponent'] . "</td>";
                    echo "<td>" . $row['site'] . "</td>";
                    echo "<td>" . $row['result'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

        </body>
    </html>

    <?php
} else {
    echo "No games found.";
}
$conn->close();
?>
