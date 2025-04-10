<?php
  
$servername = "localhost";
$username = "root"; 
$password = "$";
$dBName = "courses_database"; 


$conn = mysqli($servername, $username, $password, $dBName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to database using MySQLi";


