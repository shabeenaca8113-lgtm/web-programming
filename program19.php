<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");


mysqli_select_db($conn, $dbname);


$sql_create_table = "CREATE TABLE IF NOT EXISTS book_details (
    bookno INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    edition VARCHAR(50) NOT NULL,
    publisher VARCHAR(100) NOT NULL
)";

mysqli_query($conn, $sql_create_table);

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $bookno = $_POST["bookno"];
    $title = $_POST["title"];
    $edition = $_POST["edition"];
    $publisher = $_POST["publisher"];

    
    $check = mysqli_prepare($conn,
        "SELECT bookno FROM book_details WHERE bookno = ?");

    mysqli_stmt_bind_param($check, "i", $bookno);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {

        $message = "Warning! Book number already exists.";

    } else {

        // Insert new book
        $stmt = mysqli_prepare($conn,
            "INSERT INTO book_details
            (bookno, title, edition, publisher)
            VALUES (?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $bookno,
            $title,
            $edition,
            $publisher
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Book information added successfully!";
        } else {
            $message = "Error adding book information.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_stmt_close($check);
}


$result = mysqli_query($conn, "SELECT * FROM book_details");

?>

<html>
<head>
    <title>Book Details</title>
</head>

<body>

<h2>Enter Book Details</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <label>Book No:</label>
    <input type="number" name="bookno" required>
    <br><br>

    <label>Title:</label>
    <input type="text" name="title" required>
    <br><br>

    <label>Edition:</label>
    <input type="text" name="edition" required>
    <br><br>

    <label>Publisher:</label>
    <input type="text" name="publisher" required>
    <br><br>
<input type="submit" value="Add Book">

</form>

<br>

<!-- Display message -->
<?php
if ($message != "") {
    echo "<p><b>" . htmlspecialchars($message) . "</b></p>";
}
?>

<!-- Display all book records -->
<h2>Book Details</h2>

<table border="1" cellpadding="8">

<tr>
    <th>Book No</th>
    <th>Title</th>
    <th>Edition</th>
    <th>Publisher</th>
</tr>

<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>
            <td>" . htmlspecialchars($row['bookno']) . "</td>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($row['edition']) . "</td>
            <td>" . htmlspecialchars($row['publisher']) . "</td>
        </tr>";

    }

} else {

    echo "<tr><td colspan='4'>No books found.</td></tr>";

}

?>

</table>

</body>
</html>

<?php
mysqli_close($conn);
?>
