<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user in database
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Role-based redirection to php pages
        if ($user['role'] == 'teacher') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'teacher';
            header("Location: teacher_page.php");
        } elseif ($user['role'] == 'student') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'student';
            header("Location: student.php");
        } else {
            echo "Invalid role!";
        }
    } else {
        echo "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NUST Attendance Management System</title>
  <link rel="stylesheet" href="attendance.css">
</head>
<body class="login" style="align-items: center; display: flex;margin-left: auto;">
  <main class="main-container">
    <header>
      <h1>NUST Attendance Management System</h1>
    </header>
    <div class="login-container">
      <h2>Sign in</h2>
      <form action="" method="post">
          <div class="form-group">
              <label for="email">Username:</label>
              <input type="email" id="email" name="email" placeholder="xyz@nust.edu.pk"   required>
          </div>
          <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password"  placeholder="*****"  required>
          </div>
          <button type="submit">Login</button>
      </form>
    </div>
  </main>
</body>
</html>
