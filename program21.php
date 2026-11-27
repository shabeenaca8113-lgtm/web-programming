
<!DOCTYPE html>
<html>
<head>
    <title>Employee Details</title>

    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        input {
            padding: 6px;
            margin: 5px;
        }

        button {
            background: black;
            color: white;
            border: none;
            padding: 10px 18px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
        }

        th {
            background: black;
            color: white;
        }
    </style>
</head>

<body>

<h2>Add Employee</h2>

<form method="POST">

    Employee ID:
    <input type="number" name="emp_id" required>
    <br><br>

    Employee Name:
    <input type="text" name="emp_name" required>
    <br><br>

    Designation:
    <input type="text" name="designation" required>
    <br><br>

    Salary:
    <input type="number" name="salary" required>
    <br><br>

    Email:
    <input type="email" name="email" required>
    <br><br>

    <button type="submit" name="submit">Add Employee</button>

</form>

<?php

$conn = mysqli_connect(
    "localhost", "root", "", "employee_db"
);

if (!$conn) {
    die("Connection failed");
}

if (isset($_POST['submit'])) {

    $emp_id = $_POST['emp_id'];
    $emp_name = $_POST['emp_name'];
    $designation = $_POST['designation'];
    $salary = $_POST['salary'];
    $email = $_POST['email'];

    // Check duplicate Employee ID

    $check = mysqli_query(
        $conn,
        "SELECT * FROM employees WHERE emp_id='$emp_id'"
    );

    if (mysqli_num_rows($check) > 0) {

        echo "<p style='color:red;'>
        Error: Employee ID already exists!
        </p>";

    } else {

        $sql = "INSERT INTO employees
        (emp_id, emp_name, designation, salary, email)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt, "issis",
            $emp_id, $emp_name, $designation, $salary, $email
        );

        if (mysqli_stmt_execute($stmt)) {

            echo "<p style='color:green;'>
            Employee details inserted successfully!
            </p>";

        } else {

            echo "<p>Error inserting employee details.</p>";

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<hr>

<h2>ALL EMPLOYEE DETAILS</h2>

<table>

<tr>
    <th>Employee ID</th>
    <th>Name</th>
    <th>Designation</th>
    <th>Salary</th>
    <th>Email</th>
</tr>

<?php

$result = mysqli_query($conn, "SELECT * FROM employees");

while ($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";

    echo "<td>" . htmlspecialchars($row['emp_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['designation']) . "</td>";
    echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";

    echo "</tr>";
}

mysqli_close($conn);

?>

</table>

</body>
</html>
