<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'Mysql@9865', 'student_management', 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all distinct courses for the course dropdown
$courses_sql = "SELECT DISTINCT course FROM students";
$courses_result = $conn->query($courses_sql);

// Check if the course filter is applied
$course_filter = '';
if (isset($_GET['course']) && $_GET['course'] !== '') {
    $course_filter = $_GET['course'];
}

// Fetch student data based on the course filter
$sql = "SELECT roll_no, name, course, date_of_admission FROM students";
if ($course_filter) {
    $sql .= " WHERE course = '$course_filter'";
}
$result = $conn->query($sql);

// Handle student deletion if a delete request is made
if (isset($_POST['delete_student'])) {
    $roll_no_to_delete = $_POST['roll_no'];
    
    // Use prepared statement for security
    $delete_sql = "DELETE FROM students WHERE roll_no = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $roll_no_to_delete);

    if ($stmt->execute()) {
        echo "Student deleted successfully!";
        header("Location: view_student.php"); // Refresh the page after deletion
        exit();
    } else {
        echo "Error deleting student: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: rgb(100, 171, 248);
            color: white;
            margin: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(100, 171, 248);
            color: white;
            padding: 20px 40px;
        }

        .home-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 25px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .home-btn:hover {
            background-color: #f8f9fa;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        select {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 200px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .selected-row {
            background-color: #ffeb3b; /* Yellow background for selected row */
        }

        .delete-btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .delete-btn-container input[type="submit"] {
            padding: 15px 30px;
            background-color: #d32f2f;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn-container input[type="submit"]:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <header>
        <h1>Students List</h1>
        <a href="index.html" class="home-btn">Home</a>
    </header>

    <div class="container">
        <!-- Course Filter -->
        <div class="filter-container">
            <form method="GET" action="view_student.php">
                <label for="course" aria-label="Select Course">Select Course:</label>
                <select name="course" id="course" onchange="this.form.submit()">
                    <option value="">--All Courses--</option>
                    <?php
                    // Display the courses in the dropdown
                    if ($courses_result->num_rows > 0) {
                        while ($course_row = $courses_result->fetch_assoc()) {
                            $selected = ($course_row['course'] == $course_filter) ? 'selected' : '';
                            echo "<option value='{$course_row['course']}' {$selected}>{$course_row['course']}</option>";
                        }
                    }
                    ?>
                </select>
            </form>
        </div>

        <!-- Students Table -->
        <?php if ($course_filter): ?>
        <table id="studentsTable">
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Course</th>
                <th>Date of Admission</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-roll-no='{$row['roll_no']}'>
                            <td>{$row['roll_no']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['course']}</td>
                            <td>{$row['date_of_admission']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No students found for this course</td></tr>";
            }
            ?>
        </table>
        <?php endif; ?>

        <!-- Delete Button -->
        <div class="delete-btn-container">
            <form method="POST" action="view_student.php">
                <input type="hidden" id="selectedRollNo" name="roll_no">
                <input type="submit" class="btn" name="delete_student" value="Delete Selected Student" disabled id="deleteButton">
            </form>
        </div>
    </div>

    <script>
        // Handle row selection and row color change
        let selectedRow = null;
        const table = document.getElementById('studentsTable');
        const deleteButton = document.getElementById('deleteButton');
        const selectedRollNoInput = document.getElementById('selectedRollNo');

        table.addEventListener('click', function(event) {
            const row = event.target.closest('tr');
            if (row && row.parentNode === table.tBodies[0]) {
                if (selectedRow) {
                    selectedRow.classList.remove('selected-row');
                }
                row.classList.add('selected-row');
                selectedRow = row;

                // Set roll_no to the hidden input for deletion
                selectedRollNoInput.value = row.getAttribute('data-roll-no');

                // Enable the delete button
                deleteButton.disabled = false;
            }
        });
    </script>

</body>
</html>
