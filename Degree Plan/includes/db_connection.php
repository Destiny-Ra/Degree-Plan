<?php

$servername = "localhost";
$username = "root"; 
$password = "2005Love05$";
$databaseName = "courses_database"; 


$conn = new mysqli($servername, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection FAILED". $conn->connect_error);
}

echo "Connected to database using MySQLi";

?>