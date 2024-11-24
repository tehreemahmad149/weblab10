<?php
session_start();
include 'connection.php';

// Ensure only students can access this page
if ($_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Retrieve student ID from session
$student_id = $_SESSION['user_id'];

// Fetch classes and attendance for the student
$sql = "
    SELECT 
        c.id AS class_id, 
        c.starttime, 
        c.endtime, 
        c.credit_hours, 
        COUNT(a.isPresent) AS classes_held,
        SUM(a.isPresent) AS classes_attended
    FROM 
        class c
    JOIN 
        attendance a 
    ON 
        c.id = a.classid
    WHERE 
        a.studentid = ?
    GROUP BY 
        c.id, c.starttime, c.endtime, c.credit_hours";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for rendering
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance.css">
    <link rel="stylesheet" href="student.css">
    <title>Student Attendance View</title>
</head>
<body>
    <header class="student-header">
        <h1>Student Attendance View</h1>
    </header>
    
    <main class="student-view-container">
        <h2>Your Attendance</h2>

        <!-- Check if there are courses to display -->
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <?php 
                    $attendance = ($course['classes_attended'] / $course['classes_held']) * 100;
                    $attendance = round($attendance, 2); // Round to 2 decimal places
                    $colorClass = $attendance < 75 ? 'red' : ($attendance < 85 ? 'yellow' : 'green');
                ?>
                <div class="course-card">
                    <h2>Class ID: <?= htmlspecialchars($course['class_id']) ?></h2>
                    <p><strong>Start Time:</strong> <?= htmlspecialchars($course['starttime']) ?></p>
                    <p><strong>End Time:</strong> <?= htmlspecialchars($course['endtime']) ?></p>
                    <p><strong>Credit Hours:</strong> <?= htmlspecialchars($course['credit_hours']) ?></p>
                    <div class="progress-bar">
                        <span class="<?= $colorClass ?>" style="width: <?= $attendance ?>%;"></span>
                    </div>
                    <p>Attendance: <?= $attendance ?>%</p>
                    <p>Classes Held: <?= $course['classes_held'] ?> | Attended: <?= $course['classes_attended'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No attendance records available.</p>
        <?php endif; ?>
    </main>
</body>
</html>
