<?php
$servername = "localhost";
$username = "root";
$password = "moch1Kur!123";
$dbname = "weblab9";
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
die("Connection failed: ". $conn->connect_error);
}
echo"Connected successfully";
$sql = "SELECT * FROM attendance";
$result = $conn->query($sql);
$conn->close();
?>