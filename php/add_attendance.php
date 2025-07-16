<?php
include '/config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = "";
$courses_result = $conn->query("SELECT DISTINCT course FROM students");
$course_filter = $_POST['course'] ?? '';
$date_of_attendance = $_POST['date_of_attendance'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_attendance'])) {
    if (!$course_filter || !$date_of_attendance) {
        $success_message = "<p style='color:red;'>Please select both course and date.</p>";
    } else {
        // Delete existing attendance for this course and date to avoid duplicates
        $conn->query("DELETE a FROM attendance a
                      JOIN students s ON a.roll_no = s.roll_no
                      WHERE s.course = '" . $conn->real_escape_string($course_filter) . "'
                      AND a.date_of_attendance = '" . $conn->real_escape_string($date_of_attendance) . "'");

        // Fetch all students of this course
        $stmt_students = $conn->prepare("SELECT roll_no, name FROM students WHERE course = ?");
        $stmt_students->bind_param("s", $course_filter);
        $stmt_students->execute();
        $students_result = $stmt_students->get_result();

        $stmt_insert = $conn->prepare("INSERT INTO attendance (roll_no, name, status, date_of_attendance) VALUES (?, ?, ?, ?)");

        while ($student = $students_result->fetch_assoc()) {
            $roll_no = $student['roll_no'];
            $name = $student['name'];
            
            $status = isset($_POST['attendance'][$roll_no]) ? "Present" : "Absent";

            $stmt_insert->bind_param("ssss", $roll_no, $name, $status, $date_of_attendance);
            $stmt_insert->execute();
        }

        $stmt_insert->close();
        $success_message = "<p style='color:green;'>✅ Attendance submitted successfully for $date_of_attendance and course $course_filter.</p>";
    }
}

if (!empty($course_filter)) {
    $stmt = $conn->prepare("SELECT roll_no, name FROM students WHERE course = ?");
    $stmt->bind_param("s", $course_filter);
    $stmt->execute();
    $students = $stmt->get_result();
}

include("../attendance_form.html");
?>
