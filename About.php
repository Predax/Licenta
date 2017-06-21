<?php
session_start();
if ($_SESSION["isLogged"] == false) {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>About</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php include_once 'Navbar.php' ?>

<div class="container">
	<div id="aboutBox" class="information">
		<h2 class="information-header">Who we are</h2>
		<ul>
			<li><span>Relative new on the market</span></li>
			<li><span>5000+ requests for us</span></li>
			<li><span>1800+ content customers</span></li>
			<li><span>Very young team</span></li>
			<li><span>Entuziastic people</span></li>
			<li><span>Very dynamic and creative guys</span></li>
			<li><span>Truly professionals</span></li>
		</ul>
	</div>
</div>
</body>
</html>