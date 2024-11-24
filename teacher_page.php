<?php
session_start();
include 'connection.php';

// Ensure only teachers can access
if ($_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch classes for the logged-in teacher
$teacher_id = $_SESSION['user_id'];
$sql = "SELECT id, starttime, endtime, credit_hours FROM class WHERE teacherid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare class data for display
$classes = [];
while ($class = $result->fetch_assoc()) {
    $classes[] = $class;
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

        <!-- Check if there are classes to display -->
        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $class): ?>
                <div class="class-entry">
                    <p><strong>Class ID:</strong> <?= htmlspecialchars($class['id']) ?></p>
                    <p><strong>Start Time:</strong> <?= htmlspecialchars($class['starttime']) ?></p>
                    <p><strong>End Time:</strong> <?= htmlspecialchars($class['endtime']) ?></p>
                    <p><strong>Credit Hours:</strong> <?= htmlspecialchars($class['credit_hours']) ?></p>
                    <button class="open-roster-btn">Open Roster</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No classes assigned yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>
