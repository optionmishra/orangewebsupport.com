<?php
$servername = "localhost";
$username = "orangedb_user";
$password = "Y9M3qNU6c@QCve";
$dbname = "orange_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
