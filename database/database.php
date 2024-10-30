<?php
$host = 'localhost';
$dbname = 'grohonnc_esquireelectronicsltd';
$username_db = 'grohonnc_esquireelectronicsltd'; // Same as database name
$password_db = 'grohonnc_esquireelectronicsltd'; // Same as database name

// Create a MySQL connection
$conn = mysqli_connect($host, $username_db, $password_db, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>