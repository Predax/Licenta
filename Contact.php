<?php
session_start();
if ($_SESSION["isLogged"] == true) {
if(isset($_POST["subject"]) && isset($_POST["contactText"])) {
	include 'Connection.php';
	
	if (!empty($_POST["subject"]) && !empty($_POST["contactText"])) {
		$nowFormat = date("Y.m.d");
		$sql = "INSERT INTO messages (Subject, Content, Username, Date_reception) VALUES ('" . $_POST["subject"] . "', '" . $_POST["contactText"] . "', '" . $_SESSION["username"] . "', '" . $nowFormat . "')"; 
		if ($conn->query($sql) === TRUE) {
			$newRecord = TRUE;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
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
	<title>Contact</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php include_once 'Navbar.php' ?>
<div class="container">
	<div id="contactBox" class="information">
		<h2 class="information-header">Contact Us</h2>
		<form id="contactFrm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" class="information-container">
			<input id="contactField" name="subject" type="text" placeholder="Subject" maxlength="45"><br>
		</form>
		<textarea form ="contactFrm" name="contactText" maxlength="450"></textarea>
		<button type="submit" form="contactFrm" value="Submit">Send</button>
	</div>
</div>
</body>
</html>