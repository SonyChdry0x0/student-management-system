<?php
include '../config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$courses_sql = "SELECT DISTINCT course FROM students";
$courses_result = $conn->query($courses_sql);


$course_filter = $_GET['course'] ?? $_POST['course'] ?? '';


if (isset($_POST['delete_selected']) && isset($_POST['selected_rolls'])) {
    $rolls = $_POST['selected_rolls'];
    $placeholders = implode(',', array_fill(0, count($rolls), '?'));
    $types = str_repeat('s', count($rolls));
    $stmt = $conn->prepare("DELETE FROM students WHERE roll_no IN ($placeholders)");
    $stmt->bind_param($types, ...$rolls);
    $stmt->execute();

    header("Location: view_student.php?course=" . urlencode($course_filter));
    exit();
}

if (isset($_POST['download_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="students_list.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Roll No', 'Name', 'Course', 'Phone Number', 'Date of Admission']);

    $sql = "SELECT roll_no, name, course, phone_number, date_of_admission FROM students";
    $params = [];
    $types = '';

    if ($course_filter) {
        $sql .= " WHERE course = ?";
        $params[] = $course_filter;
        $types .= 's';
    }

    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        fputcsv($output, [
            $row['roll_no'],
            $row['name'],
            $row['course'],
            $row['phone_number'],
            $row['date_of_admission']
        ]);
    }
    fclose($output);
    exit();
}



$sql = "SELECT roll_no, name, course, phone_number,date_of_admission FROM students";
if ($course_filter) {
    $sql .= " WHERE course = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $course_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$conn->close();
include '../view_student.html';
?>
