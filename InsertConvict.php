<?php
session_start();
if ($_SESSION["isLogged"] == true) {
$startTime = date("Y-m-d");
$stopTime = date('Y-m-d', strtotime($startTime . ' +1 day'));
if(isset($_POST["fullname"])) {
	include 'Connection.php';
	/*if ($_POST["severity"] == 'class1')	{
		$increase = 1;
	} else if ($_POST["severity"] == 'class2') {
		$increase = 2;
	} else if ($_POST["severity"] == 'class3') {
		$increase = 3;
	} else if ($_POST["severity"] == 'class4') {
		$increase = 4;
	} */
	
	// $test = "+" . $increase . " years";
	//$endTime = date("Y.m.d", strtotime($test));
	
	$sql = "INSERT INTO convicts (Full_name, Begin_date, End_date) VALUES ('" . $_POST["fullname"] . "', '" . $startTime . "', '" . $_POST["releaseDate"] . "')";
	if ($conn->query($sql) === TRUE) {
		echo "INSERTED";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
}
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Convict</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<meta charset="UTF-8">
</head>
<body>
<div class="container">
	<div id="insertConvictBox" class="register">
		<h2 class="register-header">Add Convict</h2>
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" class="register-container">
			<input id="registerField" name="fullname" type="text" placeholder="Convict fullname" required="required"><br>
			<!-- <select name="severity">
				<option value="class1">I</option>
				<option value="class2">II</option>
				<option value="class3">III</option>
				<option value="class4">IV</option>
			</select><br> -->
			<input type="date" name="releaseDate" required="required" min="<?php echo htmlspecialchars($stopTime); ?>"></br>
			<input id="registerBtn" type="submit" value="Add"></input>
		</form>
	</div>
</div>
</body>
</html>