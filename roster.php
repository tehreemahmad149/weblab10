<?php
session_start();
include 'connection.php';

// Ensure the user is a teacher
if ($_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Handle POST request to save attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $attendance_data = $_POST['attendance']; // Array of student IDs and attendance statuses

    foreach ($attendance_data as $student_id => $is_present) {
        $is_present = (int)$is_present; // 1 for present, 0 for absent
        $sql = "INSERT INTO attendance (classid, studentid, isPresent, comments) 
                VALUES (?, ?, ?, '')
                ON DUPLICATE KEY UPDATE isPresent = VALUES(isPresent)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $class_id, $student_id, $is_present);
        $stmt->execute();
    }
    echo "Attendance saved successfully!";
    exit();
}

// Handle GET request to fetch the roster
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Fetch students enrolled in the class
    $sql = "
        SELECT u.id, u.fullname 
        FROM user u
        JOIN attendance a ON u.id = a.studentid
        WHERE a.classid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance.css">
    <link rel="stylesheet" href="teacher.css">
    <title>Class Roster</title>
    <script>
        function toggleAttendance(button, studentId) {
            const input = document.getElementById(`attendance-${studentId}`);
            if (input.value === "1") {
                input.value = "0";
                button.classList.remove("present");
                button.classList.add("absent");
                button.textContent = "Absent";
            } else {
                input.value = "1";
                button.classList.remove("absent");
                button.classList.add("present");
                button.textContent = "Present";
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Class Roster</h1>
    </header>
    <main>
        <?php if (!empty($students)): ?>
            <form method="POST" action="roster.php">
                <input type="hidden" name="class_id" value="<?= htmlspecialchars($class_id) ?>">
                <div class="roster-container">
                    <?php foreach ($students as $student): ?>
                        <div class="student-entry">
                            <p><?= htmlspecialchars($student['fullname']) ?></p>
                            <input type="hidden" name="attendance[<?= $student['id'] ?>]" id="attendance-<?= $student['id'] ?>" value="0">
                            <button type="button" class="attendance-button absent" onclick="toggleAttendance(this, <?= $student['id'] ?>)">Absent</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="save-button">Save Attendance</button>
            </form>
        <?php else: ?>
            <p>No students are enrolled in this class.</p>
        <?php endif; ?>
    </main>
</body>
</html>
