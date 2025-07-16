<?php

include '../config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course = $_GET['course'];
if (!empty($course)) {
    $students = $conn->query("SELECT roll_no, name FROM students WHERE course = '$course'");

    echo '<table>
            <tr>
                <th>Roll No</th>
                <th>Name</th>
                <th>Present</th>
            </tr>';
    while ($row = $students->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['roll_no'] . '</td>
                <td>' . $row['name'] . '<input type="hidden" name="student_name[' . $row['roll_no'] . ']" value="' . $row['name'] . '"></td>
                <td><input type="checkbox" name="attendance[' . $row['roll_no'] . ']"></td>
              </tr>';
    }
    echo '</table>';
}

$conn->close();
?>
