<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login1.php'); // Redirect to login page if not logged in
    exit();
}

// Database connection parameters
$servername = "10.0.1.50";
$username = "net_user";
$password = "password";
$database = "employees";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);

    // Ensure all fields are provided
    if (empty($name) || empty($email) || empty($department) || empty($salary) || empty($hire_date) || empty($position)) {
        $message = "All fields are required!";
    } else {
        // Prepare and bind
        $sql = "INSERT INTO employee_data (name, email, department, salary, hire_date, position) 
                VALUES ('$name', '$email', '$department', '$salary', '$hire_date', '$position')";

        if ($conn->query($sql) === TRUE) {
            $message = "New employee added successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle employee deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Delete the employee record
    $sql = "DELETE FROM employee_data WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Employee deleted successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch employee data from MySQL
$sql = "SELECT id, name, email, department, salary, hire_date, position FROM employee_data";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            background-image: url('https://kriday-bucket.s3.ap-northeast-1.amazonaws.com/image/background.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Roboto', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h2, h3 {
            text-align: center;
            color: #fff;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            text-align: center;
        }

        /* Form Styling */
        form {
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0;
        }

        input[type="text"], input[type="email"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: white;
        }

        td {
            background-color: rgba(0, 0, 0, 0.7);
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            color: #FFD700;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            form {
                width: 100%;
                padding: 20px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Employee Management</h2>

        <!-- Add Employee Form -->
        <h3>Add Employee Details</h3>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>

            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" step="0.01" required>

            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date" required>

            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>

            <input type="submit" name="add_employee" value="Add Employee">
        </form>

        <!-- Employee List -->
        <h3>Employee List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Hire Date</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td>" . $row['name'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['department'] . "</td>
                            <td>" . $row['salary'] . "</td>
                            <td>" . $row['hire_date'] . "</td>
                            <td>" . $row['position'] . "</td>
                            <td><a href='?delete_id=" . $row['id'] . "'>Delete</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No employees found</td></tr>";
            }
            ?>
        </table>

        <br><br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
