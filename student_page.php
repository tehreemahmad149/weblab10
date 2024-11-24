<?php
function displayCourses() {
    // Example courses for a student with number of classes held and attended
    $courses = [
        ['name' => 'Mathematics', 'classes_held' => 40, 'classes_attended' => 30],
        ['name' => 'Physics', 'classes_held' => 50, 'classes_attended' => 35],
        ['name' => 'Computer Science', 'classes_held' => 45, 'classes_attended' => 40],
    ];

    foreach ($courses as $course) {
        $attendance = ($course['classes_attended'] / $course['classes_held']) * 100;
        $attendance = round($attendance, 2); // Round to 2 decimal places

        // Determine progress bar color
        $colorClass = $attendance < 75 ? 'red' : ($attendance < 85 ? 'yellow' : 'green');

        // Render course container
        echo "
        <div class='course-card'>
            <h2>{$course['name']}</h2>
            <div class='progress-bar'>
                <span class='$colorClass' style='width: {$attendance}%;'></span>
            </div>
            <p>Attendance: {$attendance}%</p>
            <p>Classes Held: {$course['classes_held']} | Attended: {$course['classes_attended']}</p>
        </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance.css">
    <link rel="stylesheet" href="courses.css">
    <title>NUST Attendance Management System</title>
</head>
<body class="course">
    <header class="student-header">
        <h1 >NUST Attendance Management System</h1>
    </header>
    <main class="courses-container">
        <?php
        displayCourses();
        ?>
    </main>
</body>
</html>
