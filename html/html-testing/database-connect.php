<?php
// Last commit $Id$
// Version Location $HeadURL$

$servername = "localhost";
$username = "root";
$password = "newpass";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>

Create a specific user
Create tables
