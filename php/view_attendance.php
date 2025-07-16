<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$courses_result = $conn->query("SELECT DISTINCT course FROM students");
$courses = [];
if ($courses_result) {
    while ($row = $courses_result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$course_filter = $_POST['course'] ?? '';
$date_filter = $_POST['filter_date'] ?? '';

// Handle deletion
if (isset($_POST['delete_attendance'])) {
    if (!empty($_POST['selected'])) {
        foreach ($_POST['selected'] as $selected) {
            list($roll_no, $date) = explode('|', $selected);
            $stmt = $conn->prepare("DELETE FROM attendance WHERE roll_no = ? AND date_of_attendance = ?");
            if (!$stmt) {
                die("Prepare failed (delete): (" . $conn->errno . ") " . $conn->error);
            }
            $stmt->bind_param("ss", $roll_no, $date);
            $stmt->execute();
        }
        $_SESSION['message'] = "Selected attendance records deleted successfully.";
    } else {
        $_SESSION['message'] = "No attendance records were selected for deletion.";
    }
    header("Location: view_attendance.php");
    exit();
}


if (isset($_POST['download_attendance'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_list.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Roll No', 'Name', 'Course', 'Status', 'Date of Attendance']);

    $sql = "SELECT a.roll_no, a.name, s.course, a.status, a.date_of_attendance
            FROM attendance a
            JOIN students s ON a.roll_no = s.roll_no";

    $conditions = [];
    $params = [];
    $types = '';

    if ($course_filter) {
        $conditions[] = "s.course = ?";
        $params[] = $course_filter;
        $types .= 's';
    }
    if ($date_filter) {
        $conditions[] = "a.date_of_attendance = ?";
        $params[] = $date_filter;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY a.date_of_attendance ASC, a.roll_no ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed (download): (" . $conn->errno . ") " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        fputcsv($output, [
            $row['roll_no'], $row['name'], $row['course'], $row['status'], $row['date_of_attendance']
        ]);
    }
    fclose($output);
    exit();
}
$sql = "SELECT a.roll_no, a.name, s.course, a.status, a.date_of_attendance
        FROM attendance a
        JOIN students s ON a.roll_no = s.roll_no";

$conditions = [];
$params = [];
$types = '';

if ($course_filter) {
    $conditions[] = "s.course = ?";
    $params[] = $course_filter;
    $types .= 's';
}
if ($date_filter) {
    $conditions[] = "a.date_of_attendance = ?";
    $params[] = $date_filter;
    $types .= 's';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY a.date_of_attendance ASC, a.roll_no ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed (fetch): (" . $conn->errno . ") " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$attendance = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
}

$conn->close();

include '../view_attendance.html';
?>
