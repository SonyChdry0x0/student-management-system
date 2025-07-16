<?php
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = $_POST['roll_no']; 
    $name = $_POST['name'];
    $course = $_POST['course'];
    $phone_number = $_POST['phone_number'];
    $date_of_admission = $_POST['date_of_admission'];

    include 'config.php';
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
  
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO students (roll_no, name, course,phone_number, date_of_admission) 
            VALUES ('$roll_no', '$name', '$course','$phone_number','$date_of_admission')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "New student added successfully!";
    } else {
        $success_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

include('../add_student.html');
?>
