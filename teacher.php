<?php
session_start();
include 'connection.php';

if ($_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$sql = "SELECT * FROM class WHERE teacherid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>Your Classes</h1>";
while ($class = $result->fetch_assoc()) {
    echo "Class ID: " . $class['id'] . " - Credit Hours: " . $class['credit_hours'] . "<br>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance.css">
    <link rel="stylesheet" href="teacher.css">
    <link rel="stylesheet" href="courses.css">
    <title>Teacher Attendance View</title>
</head>
<body>
    <header class="student-header">
        <h1>Teacher's Attendance View</h1>
    </header>
    
    <main class="teacher-view-container">
        <h2>Class Attendance</h2>

        <!-- Loop through class data and display -->
        <?php
        $classes = getClassData();
        foreach ($classes as $class) {
            $attendanceStatus = $class['attendance_marked'] ? '✔️' : '❌';
             $attendanceClass = $class['attendance_marked'] ? 'green' : 'red';
            
            echo "
            <div class='class-entry'>
                <p><strong>Date:</strong> {$class['date']}</p>
                <p><strong>Section:</strong> {$class['section']}</p>
                <p><strong>Attendance:</strong> <span class='$attendanceClass'>$attendanceStatus</span></p>
                <button class='open-roster-btn'>Open Roster</button>
            </div>";
            
        
        }
        ?>

    </main>

</body>
</html>
