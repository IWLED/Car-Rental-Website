<?php
// Connect to MySQL
$conn = mysqli_connect("localhost", "root", "") or die("Couldn't connect: " . mysqli_connect_error());

// Select the database
mysqli_select_db($conn, "rent_db");


?>
