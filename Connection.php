<?php
	$servername = "localhost";
	$username = "root";
	$password = "";

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// make deton the current db
	$db_selected = mysqli_select_db($conn, "deton");
	if (!$db_selected) {
		die ('Can\'t use deton : ' . mysql_error());
	}
?>