<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sql = "SELECT * FROM users WHERE Username = '" . $_SESSION["username"] . "'";
	$result = $conn->query($sql);
	if (isset($_POST["submit"])) {
		move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "profile/".$_FILES['fileToUpload']['name']);
		$sqlPicture = "UPDATE users SET Image = '" . $_FILES['fileToUpload']['name'] . "' WHERE Username = '" . $_SESSION["username"] . "'";
		$resultPicture = $conn->query($sqlPicture);
		$sqlData = "UPDATE users SET Firstname = '" . $_POST["firstname"] . "', Surname = '" . $_POST["surname"] . "', Phone = '" . $_POST["phone"] . "' WHERE Username ='" . $_SESSION["username"] . "'";
		$resultData = $conn->query($sqlData);
		echo "<meta http-equiv='refresh' content='0'>";
	}
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php 
include 'OwnFunctions.php';
if ($_SESSION["role"] == $_roleClient)
{
	include_once 'Navbar.php';	
} else if ($_SESSION["role"] == $_roleAdmin)
{
	include_once 'NavbarAdm.php';
}
?>

<div class="container">
	<div id="userBox" class="userArea">
		<span id="userDisplay"><?php echo $_SESSION["username"]; ?></span>
	</div>
	<div id="profileBox" class="profile">
		<form id="profileFrm" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="profile-container" enctype="multipart/form-data">	
			<?php $row = $result->fetch_assoc(); 
				if (is_null($row["Image"]) || $row["Image"] == "") {
					echo "<img id= 'profilePicture' src='profile/default.png' alt='No picture'>";
			 } else  { echo "<img id= 'profilePicture' src='profile/" . $row["Image"] . "' alt='No picture'>"; }?>
			<span>Name: </span><input name="firstname" type="text" value="<?php echo $row['Firstname'];?>"><br><br>
			<span>Surname: </span><input name="surname" type="text" value="<?php echo $row['Surname'];?>"><br><br>
			<span>Phone: </span><input name="phone" type="text" value="<?php echo $row['Phone'];?>"><br><br>
			<span>Password: </span><input name="password" type="password"><br><br>
			<input type="file" name="fileToUpload" id="fileToUploads">
			<input type="submit" value="Save" name="submit">
		</form>		
	</div>
</div>
</body>
</html>